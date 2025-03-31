<?php
require_once 'connectDB.php';

class DB_provider
{
    private $conn;

    public function __construct()
    {
        $dtb = new connectDB();
        $this->conn = $dtb->conn;
    }

    public function getAllProvider()
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT *
        FROM providers p
        WHERE p.status = 'active'
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

    public function getProviderInRange($start, $perPage)
    {
        // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT *
        FROM providers p
        WHERE p.status = 'active'
        ORDER BY p.id ASC 
        LIMIT ?, ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $start, $perPage); // "ii" đại diện cho hai số nguyên
        $stmt->execute();
        $result = $stmt->get_result();

        // Lấy dữ liệu trả về
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Đóng statement
        $stmt->close();

        return $data;
    }

    function getAddressProvider($id)
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT *
        FROM provider_address as p
        WHERE p.provider_id = ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id); // "ii" đại diện cho hai số nguyên
        $stmt->execute();
        $result = $stmt->get_result();

        // Lấy dữ liệu trả về
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Đóng statement
        $stmt->close();

        return $data[0] ?? []; // Tra ve 1 hang
    }

    function deleteProvider($id)
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
        UPDATE providers P
        SET status = 'deleted'
        WHERE p.id = ?;
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id); // "ii" đại diện cho hai số nguyên
        $stmt->execute();
        $result = $stmt->get_result();

        // Đóng statement
        $stmt->close();
    }

    function updateProvider($id, $new_name, $new_numberPhone, $new_email)
    {
        // Giải mã URL
        $name = urldecode($new_name);
        $numberPhone = urldecode($new_numberPhone);
        $email = urldecode($new_email);

        // Chuẩn bị truy vấn SQL để tránh SQL injection
        $sql = "UPDATE providers SET name = ?, numberPhone = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        // Bind các tham số
        $stmt->bind_param("sssi", $name, $numberPhone, $email, $id);

        // Thực thi truy vấn
        $result = $stmt->execute();

        // Kiểm tra xem execute có thành công không
        if (!$result) {
            error_log("Error executing statement: " . $stmt->error);
            return false;
        }

        // Đóng statement
        $stmt->close();

        return $result;
    }

    function updateProviderAddress($id, $new_numberHouse, $new_street, $new_ward, $new_district, $new_city)
    {
        // Giải mã URL
        $numberHouse = urldecode($new_numberHouse);
        $street = urldecode($new_street);
        $ward = urldecode($new_ward);
        $district = urldecode($new_district);
        $city = urldecode($new_city);

        // Chuẩn bị truy vấn SQL để tránh SQL injection
        $sql = "UPDATE provider_address SET numberHouse = ?, streetName = ?, ward = ?, district = ?, city = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        // Bind các tham số
        $stmt->bind_param("sssssi", $numberHouse, $street, $ward, $district, $city, $id);

        // Thực thi truy vấn
        $result = $stmt->execute();

        // Kiểm tra xem execute có thành công không
        if (!$result) {
            error_log("Error executing statement: " . $stmt->error);
            return false;
        }

        // Đóng statement
        $stmt->close();

        return $result;
    }

    function getProviderById($id)
    {
        // Chuẩn bị truy vấn SQL để tránh SQL injection
        $sql = "
        SELECT p.id, p.name, p.numberPhone, p.email, pa.numberHouse, pa.streetName as street, pa.district, pa.ward, pa.city 
        FROM providers p
        LEFT JOIN provider_address pa ON pa.provider_id = p.id
        WHERE p.id = ?
        ";
        $stmt = $this->conn->prepare($sql);

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        // Bind các tham số
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Thực thi truy vấn
        $result = $stmt->get_result();

        // Kiểm tra xem execute có thành công không
        if (!$result) {
            error_log("Error executing statement: " . $stmt->error);
            return false;
        }

        $data = $result->fetch_assoc(); // Lấy dữ liệu dưới dạng mảng kết hợp

        $stmt->close();
        return $data; // Trả về dữ liệu
    }

    function getProviderByName($start, $perPage, $name)
    {
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        $new_name = "%" . urldecode($name) . "%";

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT *
        FROM providers p
        WHERE p.status = 'active' AND p.name LIKE ?
        ORDER BY p.id ASC 
        LIMIT ?, ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("sii", $new_name, $start, $perPage); // "ii" đại diện cho hai số nguyên
        $stmt->execute();
        $result = $stmt->get_result();

        // Lấy dữ liệu trả về
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Đóng statement
        $stmt->close();

        return $data;
    }

    function getAllProviderByName($name)
    {
        $new_name = "%" . urldecode($name) . "%";

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT *
        FROM providers p
        WHERE p.status = 'active' AND p.name LIKE ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("s", $new_name); // "ii" đại diện cho hai số nguyên
        $stmt->execute();
        $result = $stmt->get_result();

        // Lấy dữ liệu trả về
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Đóng statement
        $stmt->close();

        return $data;
    }

    function addProvider(
        $add_providerName,
        $add_numberPhone,
        $add_email
    ) {
        $sql = "
            INSERT INTO providers (name, numberPhone, email)
            VALUES (?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $add_providerName, $add_numberPhone, $add_email);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $providerId = $this->conn->insert_id; // Lấy ID tự động được tạo ra

        $stmt->close();

        return $providerId; // Trả về ID của nhà cung cấp vừa được thêm
    }

    function addProviderAddress(
        $providerId,
        $add_numberHouse,
        $add_street,
        $add_ward,
        $add_district,
        $add_city
    ) {
        $sql = "
            INSERT INTO provider_address (provider_id, numberHouse, streetName, ward, district, city)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("isssss", $providerId, $add_numberHouse, $add_street, $add_ward, $add_district, $add_city);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $stmt->close();

        return true; // Trả về true nếu thành công, false nếu thất bại
    }
}
