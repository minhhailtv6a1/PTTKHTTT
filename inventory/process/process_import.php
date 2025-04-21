<?php
session_start();
// process/process_import.php

require_once '../connectDatabase/connectDB.php';
require_once '../connectDatabase/DB_import_invoice.php';
require_once '../connectDatabase/DB_product.php';
require_once '../connectDatabase/DB_provider.php';

$db = new connectDB();
$dbInvoice = new DB_import_invoice();
$dbProduct = new DB_product();
$dbProvider = new DB_provider();

echo "hello ababababababbabababababbaba";
echo $_SESSION['username'];
echo $_SESSION['id'];

// $json_data = file_get_contents('php://input');
// $data = json_decode($json_data, true);
// // $import_date = $data['date'];
// // echo $data['total'];
// // echo $dbProvider->getIdProviderByName($data['provider']);
// return;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // echo $data['provider'];
    // echo $data['total'];
    // echo $data['importDetails'];
    if ($data && isset($data['total']) && isset($data['provider']) && isset($data['importDetails']) && is_array($data['importDetails'])) {
        $import_details = $data['importDetails'];
        // Sử dụng $db->conn cho transaction
        $db->conn->begin_transaction();
        $success = true;
        $invoice_id = null;

        try {
            // $import_date = date('Y-m-d H:i:s');
            // Lấy thông tin của hóa đơn
            $provider_id = $dbProvider->getIdProviderByName($data['provider']);
            $emp_id = $_SESSION['id'];
            $import_date = date('Y-m-d');
            $total = $data['total'];

            // Tạo hóa đơn từ các thông tin trên
            $dbInvoice = new DB_import_invoice();
            $invoice_id = $dbInvoice->addNewInvoice($provider_id, $emp_id, $import_date, $total);

            if ($invoice_id > 0) {
                foreach ($import_details as $item) {
                    $product_id = $item['productID'];
                    $size = $item['size'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];

                    $detail_success = $dbInvoice->addDetailInvoice($invoice_id, $product_id, $size, $price, $quantity);

                    $dbProduct->updateQuantity($product_id, $size, $quantity);

                    if (!$detail_success) {
                        $success = false;
                        $error_message = "Lỗi khi thêm chi tiết phiếu nhập.";
                        break;
                    }
                }

                if ($success) {
                    $db->conn->commit();
                    $response = ['status' => 'success', 'message' => 'Thêm phiếu nhập thành công.', 'invoice_id' => $invoice_id];
                } else {
                    $db->conn->rollback();
                    $response = ['status' => 'error', 'message' => $error_message ?? 'Lỗi trong quá trình thêm phiếu nhập.'];
                }
            } else {
                $db->conn->rollback();
                $response = ['status' => 'error', 'message' => 'Lỗi khi thêm phiếu nhập chính.'];
            }
        } catch (Exception $e) {
            $db->conn->rollback();
            $response = ['status' => 'error', 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu gửi lên không hợp lệ.']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không được phép. Chỉ chấp nhận POST.']);
}

// Bạn có thể quản lý việc đóng kết nối ở một nơi khác hoặc để class connectDB quản lý
// if (isset($db->conn)) {
//     $db->conn->close();
// }
