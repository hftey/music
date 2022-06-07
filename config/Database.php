<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pwd = "qwe098poi";
    private $db = "test_music";
    private $conn = NULL;

    public function connect() {

        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exp) {
            echo "Connection Error: " . $exp->getMessage();
        }

        return $this->conn;
    }
}