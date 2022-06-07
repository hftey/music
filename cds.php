<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'config/Database.php';
include_once 'models/MusicCd.php';

$db = new Database();
$db = $db->connect();
$music_cd = new MusicCd($db);

$music_cd_id = null;
$param=explode( '/', $_SERVER['REQUEST_URI'] );
if ($param && $param[2]){

    if (!is_numeric($param[2])){
        header('HTTP/1.0 400 Bad request');
        die();
    }

    $music_cd_id = $param[2];
}

header('Access-Control-Allow-Origin: *'); // allow angular app
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET,HEAD,POST,PUT,DELETE,OPTIONS,TRACE');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    header('Content-Type: application/problem+json; charset=utf-8');
    if ($music_cd_id){
        $res = $music_cd->fetchOne($music_cd_id);

    }else{
        $res = $music_cd->fetchAll();

    }
    $resCount = $res->rowCount();

    $records = array();
    $response['status'] = 'ok';
    $response['count'] = $resCount;

    if($resCount > 0) {

        while($row = $res->fetch(PDO::FETCH_ASSOC)) {

            extract($row);
            array_push($records, array( 'id' => (int) $id, 'artist_name' => $artist_name, 'album_title' => $album_title, 'album_catalog_no' => $album_catalog_no,
                'release_year' => (int) $release_year,'genre' => $genre,'composer' => $composer,'owner' => $owner));
        }
        $response['records'] = $records;

    } else {
        header('HTTP/1.1 404 Not Found');
        die();
    }

    echo json_encode($response);


} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    $music_cd->prepareData($data);
    if ($music_cd->validateData()){
        $music_cd->postData();
        $music_cd_id = $music_cd->lastInsertId();
        header('HTTP/1.1 201 Created');
        $response['status'] = 'ok';
        $response['uri'] = '/cds/'.$music_cd_id;
    }else{
        header('HTTP/1.1 400 Bad Request');
        $response['status'] = 'error';
        $response['message'] = 'Invalid entry';

    }
    echo json_encode($response);

}else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    if (!$music_cd_id){
        header('HTTP/1.1 400 Bad Request');
        die();
    }

    $res = $music_cd->fetchOne($music_cd_id);
    if ($res->rowCount() == 0){
        header('HTTP/1.1 404 Not Found');
        die();
    }

    $data = json_decode(file_get_contents('php://input'));
    $music_cd->prepareData($data);
    if ($music_cd->validateData()){
        $music_cd->putData($music_cd_id);
        header('HTTP/1.1 200 Updated');
        $response['status'] = 'ok';
        $response['uri'] = '/cds/'.$music_cd_id;

    }else{
        header('HTTP/1.1 400 Bad Request');
        $response['status'] = 'error';
        $response['message'] = 'Invalid entry';

    }

    echo json_encode($response);

}else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    if (!$music_cd_id){
        header('HTTP/1.1 400 Bad Request');
        die();
    }

    $res = $music_cd->fetchOne($music_cd_id);
    if ($res->rowCount() == 0){
        header('HTTP/1.1 404 Not Found');
        die();
    }

    $music_cd->delete($music_cd_id);
    header('HTTP/1.1 204 Deleted');
    $response['status'] = 'ok';
    $response['message'] = 'Record deleted';
    echo json_encode($response);

}else if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // handle options for api

    header('HTTP/1.1 200 OK');
    header('Content-Type: text/html; charset=UTF-8');
    header('Date: '.gmdate('D, d M Y H:i:s T'));
    header('Content-Length: 0');

    die();

}else{
    header('HTTP/1.1 405 Method not allowed');
    $response['status'] = 'error';
    $response['message'] = 'Method not allowed';
    echo json_encode($response);

}