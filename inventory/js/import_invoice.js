function closeFakeBG1() {
  let table = document.getElementsByClassName("fakebg1")[0];
  table.style.display = "none";
}

/////////////////////////////////////////////////Print PDF//////////////////////////////////////////
var specialElementHandlers = {
  ".no-export": function (element, renderer) {
    return true;
  },
};

async function exportPDF() {
  const { jsPDF } = window.jspdf;

  // Create a new jsPDF instance
  const doc = new jsPDF("p", "pt", "a4");

  // Source element to be converted to PDF
  const element = document.getElementById("table-container"); // Wrap your table and additional elements in a container

  // Options for html2pdf
  const opt = {
    margin: 10,
    filename: "phieu_nhap_kho.pdf",
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: "pt", format: "a4", orientation: "portrait" },
  };

  // New Promise-based usage of html2pdf
  html2pdf().from(element).set(opt).save();
}
