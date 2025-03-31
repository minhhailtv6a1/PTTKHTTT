document.addEventListener("DOMContentLoaded", function () {
  let table = document.getElementsByClassName("fakebg1")[0];
  if (table != undefined)
    if (table.style.display == "block") table.style.display = "none";
});

function searchProduct() {
  const current = 1;
  const nameProduct = document.getElementById("search-product").value;
  // Tạo tham số URL
  const params = new URLSearchParams(window.location.search);

  // Đảm bảo luôn có 'page=product' trong URL
  params.set("page", "product");
  params.set("current", current);
  params.set("product_name", nameProduct);
  params.delete("option");
  params.delete("brand");
  params.delete("price1");
  params.delete("price2");
  params.delete("product_id"); // Xóa product_id
  params.delete("category_id"); // Xóa product_id

  // Cập nhật URL
  window.location.href = `index.php?${params.toString()}`;
  return false; // Ngăn form submit mặc định
}

function updateImage() {
  const fileInput = document.getElementById("image-input");
  const image = document.getElementById("second-main-img");
  // Kiểm tra nếu có file ảnh được chọn
  if (fileInput.files && fileInput.files[0]) {
    const reader = new FileReader();

    // Đọc file và gắn đường dẫn ảnh vào thẻ ảnh
    reader.onload = function (e) {
      image.src = e.target.result;
    };
    reader.readAsDataURL(fileInput.files[0]);
  }
}

function updateImageToDTB() {
  const image = document.getElementById("second-main-img");
}

function showSize(element) {
  document.querySelectorAll(".size-btn").forEach((e) => {
    e.classList.remove("active");
  });

  element.classList.add("active");

  let quantity_size = document.querySelectorAll(".stock");
  quantity_size.forEach((e) => {
    e.hidden = true;
  });

  let sizeClass = element.textContent.trim().toLowerCase();
  // alert(sizeClass);
  if (sizeClass === "tất cả") {
    document.querySelector(".size-all").hidden = false;
    // document.querySelector(".size-all").classList.add("active");
  } else {
    let stockElement = document.querySelector(
      `.size-${sizeClass.toUpperCase()}`
    );
    if (stockElement) {
      stockElement.hidden = false; // Hiển thị phần tử stock tương ứng
    } else {
      document.querySelector(".size-all").hidden = false;
    }
  }
}

function showPhoto(element) {
  document.querySelectorAll(".detail-img").forEach((e) => {
    e.classList.remove("active");
  });
  updateImage;

  document.querySelectorAll(".update-img").forEach((e) => {
    e.classList.remove("active");
  });

  element.classList.add("active");

  let selected_img = element.src;
  document.querySelector(".main-img").src = selected_img;

  let hidden_index = document.querySelector("#selected-image-index");
  if (element.classList.contains("photo1")) {
    // Phần tử có class photo1
    hidden_index.value = 1;
    // Thêm mã bạn muốn thực thi khi phần tử có class photo1 ở đây
  } else if (element.classList.contains("photo2")) {
    // Phần tử có class photo2
    hidden_index.value = 2;
  } else if (element.classList.contains("photo3")) {
    // Phần tử có class photo2
    hidden_index.value = 3;
  } else if (element.classList.contains("photo4")) {
    // Phần tử có class photo2
    hidden_index.value = 4;
  } else if (element.classList.contains("photo5")) {
    // Phần tử có class photo2
    hidden_index.value = 5;
  }
  // alert(hidden_index.value);
}

function deleteProduct() {
  showSuccessfulAlert(`Xóa sản phẩm thành công`);
  // closeFakeBG1(element)
}

function showDetail() {
  let table = document.getElementsByClassName("fakebg1")[0];
  table.style.display = "block";
}

function closeFakeBG1() {
  let table = document.getElementsByClassName("fakebg1")[0];
  table.style.display = "none";
}

function checkFormFilter() {
  let gia1 = document.getElementById("price1");
  let gia2 = document.getElementById("price2");
  let fmGia1 = document.getElementById("formattedPrice1");
  let fmGia2 = document.getElementById("formattedPrice2");

  // if (gia1.value.trim() === "" && gia2.value.trim() === "") return;

  // Kiểm tra giá trị rỗng
  if (gia1.value.trim() === "" && gia2.value.trim() !== "") {
    showFailedAlert("Chưa nhập giá bắt đầu. Hãy nhập lại!");
    fmGia1.focus();
    return;
  }

  if (gia1.value.trim() !== "" && gia2.value.trim() === "") {
    showFailedAlert("Chưa nhập giá kết thúc. Hãy nhập lại!");
    fmGia2.focus();
    return;
  }

  // Kiểm tra định dạng giá tiền (regex)
  let pattern = /^\d+$/;
  if (!pattern.test(gia1.value.trim())) {
    showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
    fmGia1.focus();
    return;
  }

  if (!pattern.test(gia2.value.trim())) {
    showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
    fmGia2.focus();
    return;
  }

  let num1 = parseInt(gia1.value.trim(), 10); // Sử dụng parseInt của JavaScript
  let num2 = parseInt(gia2.value.trim(), 10);
  console.log(num1 + num2);

  if (num1 > num2) {
    showFailedAlert("Nhập sai khoảng giá. Hãy nhập lại!");
    gia2.focus();
    return;
  }

  // Mọi thứ hợp lệ
  // showSuccessfulAlert("Xác nhận");
}

function linkFormFilter() {
  //   alert("fdfd");
  // alert(document.getElementById("price1"));
  // return checkFormFilter();
  // checkFormFilter();
  /// KIỂM TRA FORM
  let gia1 = document.getElementById("price1");
  let gia2 = document.getElementById("price2");
  let fmGia1 = document.getElementById("formattedPrice1");
  let fmGia2 = document.getElementById("formattedPrice2");

  // if (gia1.value.trim() === "" && gia2.value.trim() === "") return;

  // Kiểm tra giá trị rỗng
  if (gia1.value.trim() !== "" || gia2.value.trim() !== "") {
    if (gia1.value.trim() === "" && gia2.value.trim() !== "") {
      showFailedAlert("Chưa nhập giá bắt đầu. Hãy nhập lại!");
      fmGia1.focus();
      return;
    }

    if (gia1.value.trim() !== "" && gia2.value.trim() === "") {
      showFailedAlert("Chưa nhập giá kết thúc. Hãy nhập lại!");
      fmGia2.focus();
      return;
    }

    // Kiểm tra định dạng giá tiền (regex)
    let pattern = /^\d+$/;
    if (!pattern.test(gia1.value.trim())) {
      showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
      fmGia1.focus();
      return;
    }

    if (!pattern.test(gia2.value.trim())) {
      showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
      fmGia2.focus();
      return;
    }

    let num1 = parseInt(gia1.value.trim(), 10); // Sử dụng parseInt của JavaScript
    let num2 = parseInt(gia2.value.trim(), 10);
    console.log(num1 + num2);

    if (num1 > num2) {
      showFailedAlert("Nhập sai khoảng giá. Hãy nhập lại!");
      fmGia2.focus();
      return;
    }
  }

  /// XỬ LÝ VÀO CHUYỂN TRANG
  const selectedType = document.querySelector('input[name="raType"]:checked');
  const selectedBrand = document.querySelector('input[name="raBrand"]:checked');
  const price1 = document.getElementById("price1");
  const price2 = document.getElementById("price2");
  const current = 1;

  // Kiểm tra giá trị price1 và price2 thay vì kiểm tra chính biến
  if (
    !selectedType &&
    !selectedBrand &&
    price1.value === "" &&
    price2.value === ""
  ) {
    showFailedAlert(
      "Bạn phải chọn (Loại)/(Thương hiệu)/(Giá) trước khi xác nhận."
    );
    return false; // Ngăn việc gửi form nếu không đủ dữ liệu
  }

  // Tạo tham số URL
  const params = new URLSearchParams(window.location.search);

  // Đảm bảo luôn có 'page=product' trong URL
  params.set("page", "product");
  params.set("current", current);
  params.delete("option");
  params.delete("product_name");
  params.delete("product_id"); // Xóa product_id
  params.set("category_id", selectedType ? selectedType.value : "");
  params.set("brand", selectedBrand ? selectedBrand.value : "");
  params.set("price1", price1.value ? price1.value : "");
  params.set("price2", price2.value ? price2.value : "");

  // Cập nhật URL
  window.location.href = `index.php?${params.toString()}`;
  return false; // Ngăn form submit mặc định
}

function formatMoney(input) {
  // Kiểm tra input có tồn tại và có thuộc tính value
  if (input && input.value) {
    let rawValue = input.value.replace(/\D/g, ""); // Loại bỏ mọi ký tự không phải số

    // Định dạng lại giá trị cho người dùng
    let formattedValue = new Intl.NumberFormat("vi-VN").format(rawValue);
    return formattedValue;
  } else {
    return ""; // Trả về chuỗi rỗng nếu input không hợp lệ
  }
}

function formatCurrency(input, hiddenFieldId) {
  // Lấy giá trị gốc không định dạng
  let rawValue = input.value.replace(/\D/g, ""); // Loại bỏ mọi ký tự không phải số

  // Định dạng lại giá trị cho người dùng
  let formattedValue = new Intl.NumberFormat("vi-VN").format(rawValue);
  input.value = formattedValue; // Hiển thị giá trị đã định dạng trong ô nhập liệu

  // Gán giá trị không định dạng vào ô ẩn
  document.getElementById(hiddenFieldId).value = rawValue;
}
