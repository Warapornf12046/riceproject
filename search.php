<?php
// Include database connection
include('include/config.php');

// Set limit of records per page
$limit = 15;

// Determine current page number
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate offset for SQL query
$offset = ($page - 1) * $limit;

// Check if search query is set and not empty
if(isset($_POST['search']) && !empty($_POST['search'])){
    $search = $_POST['search'];

    // SQL query to search for members
    $sql = "SELECT * FROM `member` WHERE `Member_Id` LIKE '%$search%' OR `Member_Name` LIKE '%$search%' OR `Member_Tel` LIKE '%$search%' LIMIT $limit OFFSET $offset";

    // Get total records count for search query
    $total_records = mysqli_num_rows($mysqli_p->query($sql));

    // Calculate total pages for pagination
    $total_pages = ceil($total_records / $limit);
} else {
    // SQL query to fetch members with pagination
    $sql = "SELECT * FROM `member` LIMIT $limit OFFSET $offset";

    // Get total records count
    $total_records = mysqli_num_rows($mysqli_p->query("SELECT * FROM `member`"));

    // Calculate total pages for pagination
    $total_pages = ceil($total_records / $limit);
}

// Execute SQL query
$result = $mysqli_p->query($sql);

// Display search results or paginated members
if ($result->num_rows > 0) {
    echo '<table class="table">';
    echo '<thead><tr><th>เลือก</th><th>รหัสลูกค้า</th><th>ชื่อ-นามสกุล</th><th>เบอร์โทรศัพท์</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="radio" name="selected_member" value="'.$row["Member_Id"].'"></td>';
        echo '<td>'.$row['Member_Id'].'</td>';
        echo '<td>'.$row['Member_Name'].'</td>';
        echo '<td>'.$row['Member_Tel'].'</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';


} else {
    // Handle case when no search and no data found
    if (!isset($_POST['search']) && $result->num_rows == 0) {
        echo 'ไม่พบข้อมูลสมาชิก';
    } else {
        // Handle case when search query returns no results
        echo 'ไม่พบข้อมูลสมาชิกที่ตรงกับคำค้นหา';
    }
}

// Close database connection
$mysqli_p->close();
?>
