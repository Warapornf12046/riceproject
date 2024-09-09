function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector(".table");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        var found = false;

        // Loop through all table cells in the current row
        for (var j = 0; j < tr[i].cells.length; j++) {
            td = tr[i].cells[j];
            if (td) {
                txtValue = (td.textContent || td.innerText).toUpperCase();
                if (txtValue.indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        // แสดงหรือซ่อนแถวตามค่า found
        if (found) {
            tr[i].style.display = ""; // แสดงแถวที่พบข้อมูลที่ตรง
        } else {
            tr[i].style.display = "none"; // ซ่อนแถวที่ไม่พบข้อมูลที่ตรง
        }
    }
}
