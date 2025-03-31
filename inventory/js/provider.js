// API thông tin các thành phố quận phường tại Việt Nam
document.addEventListener("DOMContentLoaded", function () {
  const provinceSelect = document.getElementById("update-city");
  const districtSelect = document.getElementById("update-district");
  const wardSelect = document.getElementById("update-ward");

  // Lấy danh sách tỉnh/thành phố từ API
  fetch("https://provinces.open-api.vn/api/")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((province) => {
        const option = document.createElement("option");
        option.value = province.code;
        option.textContent = province.name;
        provinceSelect.appendChild(option);
      });
    });

  // Khi chọn tỉnh/thành phố, lấy danh sách quận/huyện
  provinceSelect.addEventListener("change", function () {
    const provinceCode = this.value;
    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

    if (provinceCode) {
      fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
        .then((response) => response.json())
        .then((data) => {
          data.districts.forEach((district) => {
            const option = document.createElement("option");
            option.value = district.code;
            option.textContent = district.name;
            districtSelect.appendChild(option);
          });
        });
    }
  });

  // Khi chọn quận/huyện, lấy danh sách phường/xã
  districtSelect.addEventListener("change", function () {
    const districtCode = this.value;
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

    if (districtCode) {
      fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
        .then((response) => response.json())
        .then((data) => {
          data.wards.forEach((ward) => {
            const option = document.createElement("option");
            option.value = ward.code;
            option.textContent = ward.name;
            wardSelect.appendChild(option);
          });
        });
    }
  });
});

function showDetail() {
  let table = document.getElementsByClassName("fakebg1")[0];
  table.style.display = "block";
}

function closeFakeBG1() {
  let table = document.getElementsByClassName("fakebg1")[0];
  table.style.display = "none";
}

function searchProvider() {
  const current = 1;
  const nameProvider = document.getElementById("search-provider").value;
  //   alert(nameProvider);
  // Tạo tham số URL
  const params = new URLSearchParams(window.location.search);

  // Đảm bảo luôn có 'page=Provider' trong URL
  params.set("page", "provider");
  params.set("current", current);
  params.set("provider_name", nameProvider);
  params.delete("option");
  // params.delete("brand");
  // params.delete("price1");
  // params.delete("price2");
  // params.delete("Provider_id"); // Xóa Provider_id
  // params.delete("category_id"); // Xóa Provider_id

  // Cập nhật URL
  window.location.href = `index.php?${params.toString()}`;
  return false; // Ngăn form submit mặc định
}
