<?php
session_start();
include('include/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tech City</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link href="img\TechTeam.png" rel="icon">
</head>

<body>
    <div class="header">
        <div class="menu">
            <div class="logo">
                <img onclick="document.location='index.html'" class="img-fluid h-100" src="img/TechTeam.png" alt="">
            </div>
            <a href="member.php">บริการและสินค้า</a>
            <a href="member1.php">สินค้า</a>
            <a href="point.php">คะแนนสะสม</a>
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>
    <main>
        <section id="contact" class="contact">
            <div class="container">
                <div class="single-contact-box">
                    <h2><center>ตารางจัดอันดับสะสมแต้ม</h2></center>
                    <input class="search-box" type="text" id="searchInput" onkeyup="searchTable()" placeholder="ค้นหา...">
                    <script src="assets/js/table.js"></script>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>คะแนน</th>
                                <th>เวลาที่ใช้สมัคร</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Use MAX() function to get the maximum point value
                            $sql = "SELECT member.Member_Id, member.Member_Name, member.Member_Point, member.Member_Since
                            FROM member
                            ORDER BY member.Member_Point DESC";


                            $result = $mysqli_p->query($sql);

                            if (!$result) {
                                die("Query failed: " . $mysqli_p->error);
                            }

                            $rank = 1; // To display the ranking
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $rank++; ?></td>
                                    <td class="Member_Name">
                                        <?php echo $row['Member_Name']; ?>
                                    </td>
                                    <td class="Member_Point">
                                        <?php echo $row['Member_Point']; ?>
                                    </td>
                                    <td class="Member_Since">
                                        <?php echo $row['Member_Since']; ?>
                                    </td>
                                    
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
    </main>
</body>