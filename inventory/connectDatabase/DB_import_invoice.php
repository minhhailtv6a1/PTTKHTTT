<?php
require_once "connectDB.php";

class DB_import_invoice
{
    private $conn;

    public function __construct()
    {
        $dtb = new connectDB();
        $this->conn = $dtb->conn;
    }

    public function getAllInvoice()
    {
        $sql = "
            SELECT i.id, p.name, i.importDate, i.total
            FROM import_invoices i
            JOIN providers p on p.id = i.provider_id
            WHERE i.status = 'active'
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        // Lấy kết quả truy vấn và trả về danh sách import_invoice
        $invoices = [];
        $stmt->bind_result($invoice_id, $provider_name, $importDate, $total);

        while ($stmt->fetch()) {
            // Thêm mỗi kết quả vào mảng
            $invoices[] = [
                'id' => $invoice_id,
                'provider_name' => $provider_name,
                'importDate' => $importDate,
                'total' => $total,
            ];
        }

        $stmt->close();

        // Trả về danh sách hóa đơn nhập khẩu
        return $invoices;
    }

    public function getAllOriginalInvoice()
    {
        $sql = "
            SELECT i.id, p.name, i.importDate, i.total
            FROM import_invoices i
            JOIN providers p on p.id = i.provider_id
            WHERE i.status = 'active'
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        // Lấy kết quả truy vấn và trả về danh sách import_invoice
        $invoices = [];
        $stmt->bind_result($invoice_id, $provider_name, $importDate, $total);

        while ($stmt->fetch()) {
            // Thêm mỗi kết quả vào mảng
            $invoices[] = [
                'id' => $invoice_id,
                'provider_name' => $provider_name,
                'importDate' => $importDate,
                'total' => $total,
            ];
        }

        $stmt->close();

        // Trả về danh sách hóa đơn nhập khẩu
        return $invoices;
    }

    public function getInvoiceInRange($start, $perPage)
    {
        // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT i.id, p.name as provider_name, i.importDate, i.total
        FROM import_invoices i
        JOIN providers p on p.id = i.provider_id
        WHERE i.status = 'active'
        ORDER BY i.id ASC 
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

    public function getInvoiceInRangeByDate($start, $perPage, $date1, $date2)
    {
        // Kiểm tra đầu vào để đảm bảo $start và $perPage là số nguyên
        if (!is_int($start) || !is_int($perPage) || $start < 0 || $perPage <= 0) {
            throw new InvalidArgumentException("Invalid parameters for pagination.");
        }

        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT i.id, p.name as provider_name, i.importDate, i.total
        FROM import_invoices i
        JOIN providers p on p.id = i.provider_id
        WHERE i.status = 'active' AND i.importDate >= ? AND i.importDate <= ?
        ORDER BY i.id ASC 
        LIMIT ?, ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ssii", $date1, $date2, $start, $perPage); // "ii" đại diện cho hai số nguyên
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

    public function getInvoiceByDate($date1, $date2)
    {
        // Câu lệnh SQL với prepared statement
        $sql = "
        SELECT i.id, p.name as provider_name, i.importDate, i.total
        FROM import_invoices i
        JOIN providers p on p.id = i.provider_id
        WHERE i.status = 'active' AND i.importDate >= ? AND i.importDate <= ?
        ";

        // Chuẩn bị truy vấn và bind các tham số
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $date1, $date2); // "ii" đại diện cho hai số nguyên
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

    public function getDetailInvoice($id)
    {
        $sql = "
            SELECT i.id, p.name, i.importDate, i.total, d.product_id, d.size, d.price, d.quantity, p1.name as product_name, p1.type_id, d.id as detail_id, i.emp_id
            FROM import_invoices i
            JOIN detail_import_invoices d on i.id = d.invoice_id
            JOIN providers p on p.id = i.provider_id
            JOIN products p1 on p1.id = d.product_id
            WHERE i.status = 'active' and i.id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        // Lấy kết quả truy vấn và trả về danh sách import_invoice
        $invoices = [];
        $stmt->bind_result($invoice_id, $provider_name, $importDate, $total, $product_id, $size, $price, $quantity, $product_name, $type_id, $detail_id, $emp_id);

        while ($stmt->fetch()) {
            // Thêm mỗi kết quả vào mảng
            $invoices[] = [
                'id' => $invoice_id,
                'provider_name' => $provider_name,
                'importDate' => $importDate,
                'total' => $total,
                'product_id' => $product_id,
                'size' => $size,
                'price' => $price,
                'quantity' => $quantity,
                'product_name' => $product_name,
                'type_id' => $type_id,
                'detail_id' => $detail_id,
                'emp_id' => $emp_id
            ];
        }

        $stmt->close();

        // Trả về danh sách hóa đơn nhập khẩu
        return $invoices;
    }

    public function deleteInvoice($id)
    {
        $sql = "
            -- Đánh dấu sản phẩm đã xóa
            UPDATE import_invoices SET status = 'deleted' WHERE id = " . $id . ";
        ";
        // $stmt = $this->conn->prepare($sql);
        // if (!$stmt) {
        //     throw new Exception("Failed to prepare statement: " . $this->conn->error);
        // }
        // $stmt->bind_param("i", $id);
        $result = $this->conn->query($sql);
    }

    public function addDetailInvoice($invoice_id, $product_id, $size, $price, $quantity)
    {
        // Bắt đầu transaction để đảm bảo không có thao tác nào xen vào
        $this->conn->begin_transaction();

        try {
            $stmt = $this->conn->prepare("INSERT INTO detail_import_invoices (invoice_id, product_id, size, price, quantity) VALUES (?, ?, ?, ?, ?)");

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("iisii", $invoice_id, $product_id, $size, $price, $quantity);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Lấy ID ngay sau khi insert
            $detail_invoice_id = $this->conn->insert_id;

            $stmt->close();
            $this->conn->commit();

            // Kiểm tra giá trị ID trước khi trả về
            if ($detail_invoice_id <= 0) {
                throw new Exception("Invalid insert_id returned: " . $detail_invoice_id);
            }

            return $detail_invoice_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function deleteDetailInvoice($detail_invoice_id)
    {
        // Sử dụng prepared statement để tránh SQL Injection
        $stmt = $this->conn->prepare("DELETE FROM detail_import_invoices WHERE id = ?");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Bind tham số
        $stmt->bind_param("i", $detail_invoice_id);

        // Thực thi truy vấn
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Delete failed: " . $stmt->error);
        }

        // Kiểm tra xem có bản ghi nào bị xóa không
        $affected_rows = $stmt->affected_rows;

        $stmt->close();

        // Trả về số bản ghi bị ảnh hưởng (1 nếu xóa thành công, 0 nếu không tìm thấy bản ghi)
        return $affected_rows;
    }

    public function updateDetailInvoice($detail_id, $quantity, $price, $size)
    {
        // Bắt đầu transaction
        $this->conn->begin_transaction();

        try {
            $sql = "UPDATE detail_import_invoices 
                SET quantity = ?, price = ?, size = ? 
                WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("iisi", $quantity, $price, $size, $detail_id);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            $this->conn->commit();

            return $affected_rows;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error updating detail invoice: " . $e->getMessage());
            return 0;
        }
    }

    public function updateInvoiceTotal($invoice_id, $total)
    {
        $sql = "UPDATE import_invoices SET total = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $total, $invoice_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function addNewInvoice($provider_id, $emp_id, $importDate, $total)
    {
        // Validate input
        if (empty($provider_id) || empty($emp_id) || empty($importDate)) {
            throw new Exception("Thiếu thông tin bắt buộc");
        }

        // Kiểm tra định dạng ngày
        if (!DateTime::createFromFormat('Y-m-d', $importDate)) {
            throw new Exception("Định dạng ngày không hợp lệ (YYYY-MM-DD)");
        }

        $sql = "INSERT INTO import_invoices (provider_id, emp_id, importDate, total, status) 
                VALUES (?, ?, ?, ?, 'active')";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Ép kiểu để đảm bảo đúng kiểu dữ liệu
        $provider_id = (int)$provider_id;
        $emp_id = (int)$emp_id;
        $total = (float)$total;

        $stmt->bind_param("iisd", $provider_id, $emp_id, $importDate, $total);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Execute failed: " . $error);
        }

        $invoice_id = $stmt->insert_id;
        $stmt->close();

        return $invoice_id;
    }
}
