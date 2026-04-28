<?php
class Database
{
    private $host = "mysql";
    private $user = "root";
    private $pass = "";
    private $db   = "db_login";

    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->db
            );
        } catch (Exception $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
