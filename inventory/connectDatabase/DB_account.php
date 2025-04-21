<?php
require_once 'connectDB.php';

class DB_account
{
    private $conn;

    public function __construct()
    {
        $dtb = new connectDB();
        $this->conn = $dtb->conn;
    }

    public function getAllAccount()
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
            SELECT *
            FROM accounts a
            ";

        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function addUser($name, $date)
    {
        $sql = "
            INSERT INTO users (name, dateEnroll)
            VALUES (?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $name, $date);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $user_id = $this->conn->insert_id; // Lấy ID tự động được tạo ra

        $stmt->close();

        return $user_id; // Trả về ID của nhà cung cấp vừa được thêm
    }
    public function addAccount($user_id, $accountName, $username, $password, $numberPhone)
    {
        $sql = "
            INSERT INTO accounts (user_id, name, userName, passWord, numberPhone, level)
            VALUES (?, ?, ?, ?, ?, 'inventoryStaff')
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("issss", $user_id, $accountName, $username, $password, $numberPhone);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        // $providerId = $this->conn->insert_id; // Lấy ID tự động được tạo ra

        $stmt->close();

        // return $providerId; // Trả về ID của nhà cung cấp vừa được thêm
    }

    public function getIdUser($id)
    {
        $sql = "
    SELECT *
    FROM users
    WHERE id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về một mảng duy nhất
        } else {
            return null; // Hoặc false
        }
    }

    public function getAllInventoryStaff()
    {
        $sql = "
        SELECT *
        FROM accounts a
        WHERE a.level = 'inventoryStaff' and status = 'active';
    ";

        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
