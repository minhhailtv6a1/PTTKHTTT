<?php
require_once 'connectDB.php';

class DB_product
{
    private $conn;

    public function __construct()
    {
        $dtb = new connectDB();
        $this->conn = $dtb->conn;
    }
    public function render()
    {
        $sql = "INSERT INTO product_images (product_id, image_path1, image_path2, image_path3, image_path4, image_path5) VALUES ";
        for ($i = 1; $i <= 35; $i++) {
            $sql .= "(
            " . $i . ",
            'assets/img/img_product/chiTietAnhSP/SP_" . $i . "/anh1.png',
            'assets/img/img_product/chiTietAnhSP/SP_" . $i . "/anh2.png',
            'assets/img/img_product/chiTietAnhSP/SP_" . $i . "/anh3.png',
            'assets/img/img_product/chiTietAnhSP/SP_" . $i . "/anh4.png',
            'assets/img/img_product/chiTietAnhSP/SP_" . $i . "/anh5.png'
        )";
            if ($i < 35) {
                $sql .= ",";
            } else {
                $sql .= ";";
            }
        }
        $result = $this->conn->query($sql);
    }

    // public function getAllProductDB()
    // {
    //     $sql = "
    //     SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity  
    //     FROM products p  
    //     JOIN quantity_by_size qbs ON p.id = qbs.product_id  
    //     WHERE p.status = 'active'
    //     GROUP BY p.id, p.name, p.price
    //     ";
    //     $result = $this->conn->query($sql);

    //     $data = [];
    //     if ($result) {
    //         while ($row = $result->fetch_assoc()) {
    //             $data[] = $row;
    //         }
    //     }
    //     return $data;
    // }

    public function getAllProductDB($category_id, $brand, $price1, $price2)
    {
        $query = "";
        if ($category_id != "") {
            $query .= " AND p.type_id = $category_id";
        }
        if ($brand != "") {
            $query .= " AND p.brand = '$brand'";
        }
        if ($price1 != "" && $price2 != "") {
            $query .= " AND p.price >= $price1 AND p.price <= $price2";
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity  
        FROM products p  
        LEFT JOIN quantity_by_size qbs ON p.id = qbs.product_id  
        WHERE p.status = 'active' " . $query . "
        GROUP BY p.id, p.name, p.price
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


    public function deleteProduct($id)
    {
        $sql = "
            -- Đánh dấu sản phẩm đã xóa
            UPDATE products SET status = 'deleted' WHERE id = " . $id . ";
        ";
        $result = $this->conn->query($sql);
    }

    public function updateProduct($id, $new_name, $new_category_id, $new_brand, $new_price)
    {
        // Giải mã URL
        $name = urldecode($new_name);
        $category_id = urldecode($new_category_id);
        $brand = urldecode($new_brand);
        $price = urldecode($new_price);

        // Chuẩn bị truy vấn SQL để tránh SQL injection
        $sql = "UPDATE products SET name = ?, type_id = ?, brand = ?, price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        // Bind các tham số
        $stmt->bind_param("sisdi", $name, $category_id, $brand, $price, $id);

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

    public function updateProductImage($id, $selectedImgIndex, $selectedImage)
    {
        // Tạo tên cột  
        $att = "image_path" . $selectedImgIndex;

        // Sử dụng prepared statement  
        $sql = "UPDATE products SET " . $att . " = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // Thực hiện cập nhật  
        if ($stmt->execute([$selectedImage, $id])) {
            return true; // Cập nhật thành công  
        } else {
            return false; // Cập nhật không thành công  
        }
    }

    public function getImagesProduct($id)
    {
        $sql = "
            SELECT pi.image_path1 AS img1, pi.image_path2 AS img2, pi.image_path3 AS img3, pi.image_path4 AS img4, pi.image_path5 AS img5
            FROM product_images pi 
            WHERE pi.product_id = " . $id . "
        ";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // vì câu truy vấn trả về 1 dòng, nên chỉ cần lấy dòng đầu tiên của mảng.
        return $data[0] ?? [];
    }

    public function getProductByID($id)
    {
        $sql = "
        SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity, p.type_id
        FROM products p  
        JOIN quantity_by_size qbs ON p.id = qbs.product_id  
        WHERE p.id = " . $id . "
        GROUP BY p.id, p.name, p.price
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

    public function getAllDetailProduct($product_id)
    {
        $sql = "
        SELECT p.id, p.name, tp.name AS type, p.brand, p.price, qbs.size, qbs.quantity, p.type_id
        FROM products AS p
        LEFT JOIN quantity_by_size AS qbs ON p.id = qbs.product_id
        LEFT JOIN typeProduct AS tp ON tp.id = p.type_id
        WHERE p.id = " . $product_id . ";";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getTypeProduct($category_id)
    {
        $sql = "
        SELECT t.id, t.name
        FROM typeProduct t
        WHERE t.id = $category_id";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getFilterProduct($category_id, $brand, $price1, $price2)
    {
        $query = "";
        if ($category_id != "") {
            $query .= " AND p.type_id = $category_id";
        }
        if ($brand != "") {
            $query .= " AND p.brand = $brand";
        }
        if ($price1 != "" && $price2 != "") {
            $query .= " AND p.price >= $price1 AND p.price <= $price2";
        }
        $sql = "
        SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity  
        FROM products p  
        JOIN quantity_by_size qbs ON p.id = qbs.product_id  
        WHERE p.status = 'active' " . $query . "
        GROUP BY p.id, p.name, p.price
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

    public function getProductInRange($start, $perPage, $category_id, $brand, $price1, $price2)
    {
        $query = "";
        if ($category_id != "") {
            $query .= " AND p.type_id = $category_id";
        }
        if ($brand != "") {
            $query .= " AND p.brand = '$brand'";
        }
        if ($price1 != "" && $price2 != "") {
            $query .= " AND p.price >= $price1 AND p.price <= $price2";
        }

        // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity  
        FROM products p  
        LEFT JOIN quantity_by_size qbs ON p.id = qbs.product_id  
        WHERE p.status = 'active' " . $query . "
        GROUP BY p.id, p.name, p.price
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

    public function getAllProductByName($name)
    {
        $sql = "
        SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity
            FROM products p
            JOIN quantity_by_size qbs ON p.id = qbs.product_id
            WHERE p.status = 'active' AND p.name LIKE '%$name%'
            GROUP BY p.id, p.name, p.price";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getProductByName($start, $perPage, $name)
    {
        // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
            SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity
            FROM products p
            JOIN quantity_by_size qbs ON p.id = qbs.product_id
            WHERE p.status = 'active' AND p.name LIKE ?
            GROUP BY p.id, p.name, p.price
            ORDER BY p.id ASC
            LIMIT ?, ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $name = "%" . urldecode($name) . "%";
        $stmt->bind_param("sii", $name, $start, $perPage); // "sii" đại diện cho chuỗi, số nguyên, số nguyên

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Failed to get result: " . $stmt->error);
        }

        // Lấy dữ liệu trả về
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Đóng statement
        $stmt->close();

        return $data;
    }

    public function addQuantityBySize($id, $type_id)
    {
        $sql = ""; // Khởi tạo biến $sql

        if ($type_id == 1 || $type_id == 2) {
            $sql = "INSERT INTO quantity_by_size(product_id, size, quantity) VALUES
                (" . $id . ", 'S', 0),
                (" . $id . ", 'M', 0),
                (" . $id . ", 'L', 0),
                (" . $id . ", 'XL', 0),
                (" . $id . ", 'XXL', 0)
            ";
        } else if ($type_id == 3) {
            $sql = "INSERT INTO quantity_by_size(product_id, size, quantity) VALUES
                (" . $id . ", 'XS', 0),
                (" . $id . ", 'S', 0),
                (" . $id . ", 'M', 0),
                (" . $id . ", 'L', 0)
            ";
        } else if ($type_id == 4 || $type_id == 5) {
            $sql = "INSERT INTO quantity_by_size(product_id, size, quantity) VALUES
                (" . $id . ", '38', 0),
                (" . $id . ", '39', 0),
                (" . $id . ", '40', 0),
                (" . $id . ", '41', 0),
                (" . $id . ", '42', 0)
            ";
        } else if ($type_id == 6) {
            $sql = "INSERT INTO quantity_by_size(product_id, size, quantity) VALUES
                (" . $id . ", '28', 0),
                (" . $id . ", '30', 0),
                (" . $id . ", '32', 0),
                (" . $id . ", '34', 0)
            ";
        }

        // Kiểm tra xem $sql có giá trị trước khi thực hiện
        if (!empty($sql)) {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }

            $stmt->close(); // Thêm đóng statement
        } else {
            // Xử lý trường hợp $type_id không hợp lệ nếu cần
            throw new Exception("Loại sản phẩm không hợp lệ: " . $type_id);
        }
    }


    public function addProduct($name, $type_id, $brand, $price)
    {
        // Sử dụng prepared statements để tránh SQL Injection
        $stmt = $this->conn->prepare("INSERT INTO products (name, type_id, brand, price) VALUES (?, ?, ?, ?)");

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            return false; // Trả về false nếu prepare thất bại
        }

        // Bind parameters
        $stmt->bind_param("sisi", $name, $type_id, $brand, $price);

        // Thực thi truy vấn
        $result = $stmt->execute();
        $product_id = $this->conn->insert_id;
        $this->addQuantityBySize($product_id, $type_id);
        // Kiểm tra kết quả
        if ($result) {
            return true; // Trả về true nếu thêm thành công
            // Hoặc bạn có thể trả về ID của sản phẩm vừa thêm:
            // return $this->conn->insert_id;
        } else {
            return false; // Trả về false nếu thêm thất bại
        }

        // Đóng statement
        $stmt->close();
    }

    public function initDetailProduct($id, $category_id)
    {
        // Sử dụng prepared statements để tránh SQL Injection
        $sql = "";
        switch ($category_id) {
            case 1:
            case 2:
                $sql = "
            INSERT INTO quantity_by_size (product_id, size, quantity) VALUES 
            (" . $id . ", 'S', 0),
            (" . $id . ", 'M', 0),
            (" . $id . ", 'L', 0),
            (" . $id . ", 'XL', 0),
            (" . $id . ", 'XXL', 0);";
                break;
            case 3:
                $sql = "
                INSERT INTO quantity_by_size (product_id, size, quantity) VALUES 
                (" . $id . ", 'XS', 0),
                (" . $id . ", 'S', 0),
                (" . $id . ", 'M', 0),
                (" . $id . ", 'L', 0);";
                break;
            case 4:
            case 5:
                $sql = "
                INSERT INTO quantity_by_size (product_id, size, quantity) VALUES 
                (" . $id . ", '38', 0),
                (" . $id . ", '39', 0),
                (" . $id . ", '40', 0),
                (" . $id . ", '41', 0),
                (" . $id . ", '42', 0);";
                break;
            case 6:
                $sql = "
                INSERT INTO quantity_by_size (product_id, size, quantity) VALUES 
                (" . $id . ", '28', 0),
                (" . $id . ", '30', 0),
                (" . $id . ", '32', 0),
                (" . $id . ", '34', 0);";
                break;
        }
        $result = $this->conn->query($sql);
    }



    // public function getProductInRange($start, $perPage)
    // {
    //     // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
    //     if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
    //         throw new InvalidArgumentException("Invalid parameters for pagination.");
    //     }

    //     // Câu lệnh SQL với prepared statement
    //     $sql = "
    //     SELECT p.id, p.name, p.price, SUM(qbs.quantity) AS quantity  
    //     FROM products p  
    //     JOIN quantity_by_size qbs ON p.id = qbs.product_id  
    //     WHERE p.status = 'active'
    //     GROUP BY p.id, p.name, p.price
    //     ORDER BY p.id ASC 
    //     LIMIT ?, ?
    //     ";

    //     // Chuẩn bị truy vấn và bind các tham số
    //     $stmt = $this->conn->prepare($sql);
    //     if (!$stmt) {
    //         throw new Exception("Failed to prepare statement: " . $this->conn->error);
    //     }

    //     $stmt->bind_param("ii", $start, $perPage); // "ii" đại diện cho hai số nguyên
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     // Lấy dữ liệu trả về
    //     $data = [];
    //     if ($result) {
    //         while ($row = $result->fetch_assoc()) {
    //             $data[] = $row;
    //         }
    //     }

    //     // Đóng statement
    //     $stmt->close();

    //     return $data;
    // }

    public function getAllProductForInvoice()
    {
        $sql = "SELECT * FROM products WHERE status = 'active'";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Failed to get result: " . $stmt->error);
        }
        return $result;
    }
    public function getQuantity($product_id, $size)
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
                SELECT *
                FROM quantity_by_size qbs
                WHERE qbs.product_id = ? AND qbs.size = ?
            ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("is", $product_id, $size); // "sii" đại diện cho chuỗi, số nguyên, số nguyên

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Failed to get result: " . $stmt->error);
        }

        // Lấy dữ liệu trả về
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Đóng statement
        $stmt->close();

        return $data;
    }
    public function updateQuantity($product_id, $size, $quantity)
    {
        $quantityData = $this->getQuantity($product_id, $size);

        if (!empty($quantityData)) {
            if (isset($quantityData[0]['quantity'])) {
                $oldQuantity = intval($quantityData[0]['quantity']);
                $newQuantity = $oldQuantity + $quantity;

                // Câu lệnh SQL UPDATE với prepared statement
                $sql = "
                    UPDATE quantity_by_size
                    SET quantity = ?
                    WHERE product_id = ? AND size = ?
                ";

                // Chuẩn bị truy vấn và bind các tham số
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $this->conn->error);
                }

                $stmt->bind_param("iis", $newQuantity, $product_id, $size);

                if (!$stmt->execute()) {
                    throw new Exception("Failed to execute statement: " . $stmt->error);
                }

                // Lấy số hàng bị ảnh hưởng (nếu cần)
                $affectedRows = $stmt->affected_rows;

                // Đóng statement
                $stmt->close();

                return $affectedRows; // Trả về số hàng bị ảnh hưởng
            } else {
                throw new Exception("Dữ liệu số lượng không hợp lệ cho sản phẩm ID: $product_id và size: $size.");
            }
        } else {
            throw new Exception("Không tìm thấy số lượng cho sản phẩm ID: $product_id và size: $size.");
            // Hoặc bạn có thể quyết định tạo một bản ghi mới ở đây nếu cần
        }
    }
}
