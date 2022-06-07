<?php

class MusicCd {

    private $conn;
    
    public $id;
    public $artist_name;
    public $album_title;
    public $album_catalog_no;
    public $release_year;
    public $genre;
    public $composer;
    public $owner;

    public function __construct($db){
        $this->conn = $db;
    }

    public function lastInsertId(){
        return $this->conn->lastInsertId();
    }

    public function prepareData($data){

        $this->artist_name = $data->artist_name;
        $this->album_title  = $data->album_title;
        $this->album_catalog_no  = $data->album_catalog_no;
        $this->release_year  = $data->release_year;
        $this->genre  = $data->genre;
        $this->composer  = $data->composer;
        $this->owner  = $data->owner;
    }

    public function isEmpty($val){
        if (strlen($val) == 0){
            return true;
        }
        return false;
    }

    public function validateData(){
        if ($this->isEmpty($this->artist_name) || $this->isEmpty($this->album_title) || $this->isEmpty($this->release_year) || $this->isEmpty($this->genre)
            || $this->isEmpty($this->owner) || intval($this->release_year) <= 1900 ){
            return false;
        }
        return true;
    }

    public function bindData($stmt){


        $stmt->bindValue(':artist_name', $this->artist_name, PDO::PARAM_STR);
        $stmt->bindValue(':album_title', $this->album_title, PDO::PARAM_STR);
        $stmt->bindValue(':album_catalog_no', $this->album_catalog_no, PDO::PARAM_STR);
        $stmt->bindValue(':release_year', $this->release_year, PDO::PARAM_INT);
        $stmt->bindValue(':genre', $this->genre, PDO::PARAM_STR);
        $stmt->bindValue(':composer', $this->composer, PDO::PARAM_STR);
        $stmt->bindValue(':owner', $this->owner, PDO::PARAM_STR);

        return $stmt;
    }

    public function fetchAll() {
        
        $stmt = $this->conn->prepare('SELECT * FROM music_cd');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne($music_cd_id) {

        $stmt = $this->conn->prepare('SELECT  * FROM music_cd WHERE id = :id');
        $stmt->execute(array(':id'=>$music_cd_id));

        return $stmt;

    }

    public function postData() {

        $stmt = $this->conn->prepare('INSERT INTO music_cd SET artist_name = :artist_name, album_title = :album_title, album_catalog_no = :album_catalog_no, '.
            'release_year = :release_year, genre = :genre, composer = :composer, owner = :owner');

        $stmt = $this->bindData($stmt);
        if($stmt->execute()) {
            return TRUE;
        }

        return FALSE;
    }

    public function putData($music_cd_id) {


        $stmt = $this->conn->prepare('UPDATE music_cd SET artist_name = :artist_name, album_title = :album_title, album_catalog_no = :album_catalog_no, '.
            'release_year = :release_year, genre = :genre, composer = :composer, owner = :owner WHERE id = :id');

        $stmt = $this->bindData($stmt);
        $stmt->bindValue(':id', $music_cd_id, PDO::PARAM_INT);

        if($stmt->execute()) {
            return TRUE;
        }

        return FALSE;

    }

    public function delete($music_cd_id) {

        $stmt = $this->conn->prepare('DELETE FROM music_cd WHERE id = :id');
        $stmt->bindParam(':id', $music_cd_id);

        if($stmt->execute()) {
            return TRUE;
        }

        return FALSE;
    }


}