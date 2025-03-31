<div class="main-content">
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    switch ($page) {
        case 'product':
            include "layout/component/product.php";
            break;
        case 'provider':
            include "layout/component/provider.php";
            break;
        case 'export_invoice':
            include "layout/component/export_invoice.php";
            break;
        case 'import_invoice':
            include "layout/component/import_invoice.php";
            break;
        default:
            include "layout/component/home.php";
            break;
    }
    ?>
</div>