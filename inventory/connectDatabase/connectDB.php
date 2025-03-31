<?php
class connectDB
{
    private $host = "localhost";
    private $db_name = "project_web2";
    private $user = "root";
    private $password = "";

    public $conn;

    public function __construct()
    {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getConnection()
    {
        try {
            $pdo = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->user,
                $this->password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Chế độ lỗi ngoại lệ
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
