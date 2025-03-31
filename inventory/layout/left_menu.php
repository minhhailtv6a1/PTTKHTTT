<div class="left-menu">
    <div class="heading">
        <div
            style="
              display: flex;
              flex-direction: row;
              justify-content: center;
              width: 100%;
            ">
            <span style="width: 95%">GE STORE</span>
            <span
                style="
                display: flex;
                flex-direction: row;
                justify-content: right;
                width: 5%;
              "><i
                    class="fa-solid fa-chevron-left icon-hover"
                    onclick="closeLeftMenu()"></i></span>
        </div>
    </div>
    <hr />
    <ul class="content-left-menu">
        <a href="index.php?page=home">
            <li class="icon-hover">
                <i class="fa-solid fa-landmark"></i><span class="menu-text">Tổng quan</span>
            </li>
        </a>

        <a href="index.php?page=product">
            <li class="icon-hover">
                <i class="fa-solid fa-box-open"></i><span class="menu-text">Sản phẩm</span>
            </li>
        </a>

        <a href="index.php?page=provider">
            <li class="icon-hover">
                <i class="fa-solid fa-handshake"></i><span class="menu-text">Nhà cung cấp</span>
            </li>
        </a>

        <a href="index.php?page=import_invoice">
            <li class="icon-hover">
                <i class="fa-solid fa-file-invoice"></i><span class="menu-text">Phiếu nhập kho</span>
            </li>
        </a>

        <a href="index.php?page=export_invoice">
            <li class="icon-hover">
                <i class="fa-solid fa-file-export"></i><span class="menu-text">Phiếu xuất kho</span>
            </li>
        </a>
    </ul>
    <script>
        function showLeftMenu() {
            let menu = document.getElementsByClassName("left-menu")[0];
            menu.classList.add("open");
        }

        function closeLeftMenu() {
            let menu = document.getElementsByClassName("left-menu")[0];
            menu.classList.remove("open");
        }

        function showContentHeader() {
            let menu = document.getElementsByClassName("content-header")[0];
            menu.classList.add("open");
        }

        function closeContentHeader() {
            let menu = document.getElementsByClassName("content-header")[0];
            menu.classList.remove("open");
        }

        // function showDropDownMenu(element) {
        //     let dd_menu = element.getElementsByClassName("drop-down-menu")[0];
        //     dd_menu.style.display =
        //         dd_menu.style.display === "block" ? "none" : "block";

        //         let drop_down = document.getElementsByClassName("drop-down-menu");
        //     for (let menu of drop_down) {
        //         if (menu !== dd_menu) {
        //             menu.style.display = "none"; // Ẩn menu không được click
        //         }
        //     }
        // }

        function showDropDownMenu(element) {
            event.stopPropagation(); // Ngăn chặn sự kiện lan rộng

            // Lấy menu liên kết
            let dd_menu = element.getElementsByClassName("drop-down-menu")[0];

            // Chuyển đổi trạng thái hiển thị
            dd_menu.style.display =
                dd_menu.style.display === "block" ? "none" : "block";

            // Ẩn các menu khác
            let drop_downs = document.getElementsByClassName("drop-down-menu");
            for (let menu of drop_downs) {
                if (menu !== dd_menu) {
                    menu.style.display = "none";
                }
            }
        }

        // Đảm bảo khi click bên trong menu lọc, menu không bị đóng
        document.querySelectorAll(".drop-down-menu").forEach(menu => {
            menu.addEventListener("click", function(event) {
                event.stopPropagation(); // Giữ trạng thái menu
            });
        });

        document.addEventListener("click", function(e) {
            let isClickInsideDropDown = e.target.closest(".drop-down-menu");
            let isClickInsideTrigger = e.target.closest(".has-drop-down-menu");

            // Nếu không click vào menu hoặc nút kích hoạt thì ẩn tất cả menu
            if (!isClickInsideDropDown && !isClickInsideTrigger) {
                let dropDowns = document.getElementsByClassName("drop-down-menu");
                for (let menu of dropDowns) {
                    menu.style.display = "none";
                }
            }
        });
    </script>
</div>

<script>
    function handleFilterMenu() {
        const filterElement = document.querySelector(".filter"); // Lấy phần tử lọc
        const dropDownMenu = filterElement.querySelector(".drop-down-menu"); // Lấy menu liên quan

        // Khi click vào phần tử lọc, chuyển đổi trạng thái hiển thị của menu
        filterElement.addEventListener("click", function(event) {
            event.stopPropagation(); // Ngăn sự kiện lan ra ngoài

            // Chuyển đổi trạng thái hiển thị menu
            const isVisible = dropDownMenu.style.display === "block";
            dropDownMenu.style.display = isVisible ? "none" : "block";

            // Ẩn các menu khác (nếu có)
            const allMenus = document.querySelectorAll(".drop-down-menu");
            allMenus.forEach(menu => {
                if (menu !== dropDownMenu) {
                    menu.style.display = "none";
                }
            });
        });

        // Ngăn menu bị đóng khi click vào bên trong chính nó
        dropDownMenu.addEventListener("click", function(event) {
            event.stopPropagation(); // Ngăn sự kiện click kích hoạt đóng menu
        });

        // Đóng menu khi click ra ngoài vùng
        document.addEventListener("click", function() {
            dropDownMenu.style.display = "none";
            const form = document.querySelector('.drop-down-menu');
            const resetButton = document.querySelector('button[type="reset"]');
            resetButton.click();
        });
    }

    // Gọi hàm xử lý khi trang đã tải xong
    window.onload = function() {
        handleFilterMenu();
    }
</script>

<script>
    let left_menu = document.querySelectorAll(".content-left-menu li");

    let tongQuan = left_menu[0];
    let sp = left_menu[1];
    let ncc = left_menu[2];
    let pnk = left_menu[3];
    let pxk = left_menu[4];
    console.log("Tổng quan:", tongQuan);
    console.log("Sản phẩm:", sp);
    console.log("Nhà cung cấp:", ncc);
    console.log("Phiếu nhập kho:", pnk);
    console.log("Phiếu xuất kho:", pxk);
    // console.log(document.querySelectorAll(".content-left-menu li"));

    tongQuan.addEventListener("click", function() {
        tongQuan.classList.add("active");
        sp.classList.remove("active");
        ncc.classList.remove("active");
        pxk.classList.remove("active");
        pnk.classList.remove("active");
    });

    sp.addEventListener("click", function() {
        sp.classList.add("active");
        tongQuan.classList.remove("active");
        ncc.classList.remove("active");
        pxk.classList.remove("active");
        pnk.classList.remove("active");
    });

    ncc.addEventListener("click", function() {
        ncc.classList.add("active");
        sp.classList.remove("active");
        tongQuan.classList.remove("active");
        pxk.classList.remove("active");
        pnk.classList.remove("active");
    });

    pxk.addEventListener("click", function() {
        pxk.classList.add("active");
        sp.classList.remove("active");
        ncc.classList.remove("active");
        tongQuan.classList.remove("active");
        pnk.classList.remove("active");
    });

    pnk.addEventListener("click", function() {
        pnk.classList.add("active");
        sp.classList.remove("active");
        ncc.classList.remove("active");
        pxk.classList.remove("active");
        tongQuan.classList.remove("active");
    })

    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    // const page = isset($_GET['page']) ? $_GET['page'] : 'home';
    switch (page) {
        case 'home':
            tongQuan.classList.add('active');
            break;
        case 'product':
            sp.classList.add('active');
            break;
        case 'provider':
            ncc.classList.add('active');
            break;
        case 'import_invoice':
            pnk.classList.add('active');
            break;
        case 'export_invoice':
            pxk.classList.add('active');
            break;
        default:
            tongQuan.classList.add('active'); // Default to 'Tổng quan'
    }
</script>