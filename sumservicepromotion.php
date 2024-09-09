<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tech City</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link href="img\TechTeam.png" rel="icon">
    <style>
        /* CSS เพื่อจัดรูปแบบ */
        .summary {
            text-align: center;
        }

        .item {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="menu">
            <div class="logo">
                <img onclick="document.location='index.html'" class="img-fluid h-100" src="img/TechTeam.png" alt="">
            </div>
            <a href="OrderService.php">บริการและสินค้า</a>
            <a href="OrderProduct2.php">สินค้า</a>
            <a href="point.php">คะแนนสะสม</a>
            <a href="register.php" onclick="register()">สมัครสมาชิก</a>
            <a href="login.php" onclick="login()">เข้าสู่ระบบ</a>
        </div>
    </div>

    <section id="contact" class="contact">
        <div class="container">
            <div class="single-contact-box">
                <h2><b><center>สรุปรายการ</center></b></h2>
                <?php
                session_start(); // เริ่ม session

                // เชื่อมต่อกับฐานข้อมูล
                include('include/config.php');

                // กำหนดตัวแปรราคารวมทั้งหมดของบริการ
                $total_price = 0;

                // ดึงข้อมูลล่าสุดจากฐานข้อมูล (บริการ)
                $sqlService = "SELECT * FROM orderservice ORDER BY OrderService_Id DESC LIMIT 1";
                $resultService = mysqli_query($mysqli_p, $sqlService);

                // ตรวจสอบว่ามีข้อมูลในบริการหรือไม่
                $hasServiceData = mysqli_num_rows($resultService) > 0;

                if ($hasServiceData) { // ถ้ามีข้อมูลบริการอย่างเดียว
                    $rowService = mysqli_fetch_assoc($resultService);

                    // คำนวณราคารวมและแสดงรายการของบริการ
                    echo "<div class='item'>";
                    echo "<p>" . $rowService['ServiceProduct_Name'] . "</p>";
                    echo "<p>จำนวน: " . $rowService['Bucket_Service'] . " ถัง</p>";
                    echo "<p>ราคารวม: " . $rowService['Total_Service'] . " บาท</p>";
                    echo "</div>";

                    // ราคารวมทั้งหมด
                    $total_price = $rowService['Total_Service'];

                    // คำนวณคะแนนที่ได้
                    $earned_points = ceil($total_price / 100);

                    // ไอดีของสมาชิกโดยใช้ Member_Id
                    $member_id = isset($_SESSION['Member_Id']) ? $_SESSION['Member_Id'] : 0; 

                    // อัปเดตคะแนนสะสมของสมาชิก
                    $sqlUpdatePoints = "UPDATE member SET Member_Point = Member_Point + $earned_points WHERE Member_Id = $member_id";
                    $resultUpdatePoints = mysqli_query($mysqli_p, $sqlUpdatePoints);

                    // ตรวจสอบว่ามีข้อมูลถูกอัปเดตหรือไม่
                    if ($resultUpdatePoints) {
                        echo "<div class='summary'><b>คะแนนสะสมที่ได้: $earned_points</b></div>";
                    } else {
                        echo "<div class='summary'>มีข้อผิดพลาดในการเพิ่มคะแนนสะสม</div>";
                    }
                } else { // ถ้าไม่มีข้อมูลเลย
                    // แสดงข้อความ "ไม่พบข้อมูลบริการ"
                    echo "<div class='summary'>ไม่พบข้อมูลบริการ</div>";
                }

                // แสดงราคารวมทั้งหมด
                echo "<div class='summary'><b>ราคารวมทั้งหมด: " . $total_price . " บาท</b></div>";
                ?>
                <div>
                    <center>
                        <input class="btn" type="button" onclick="document.location='OrderService.php'" value="ย้อนกลับ">
                        <input class="btn" type="button" onclick="document.location='receiptpromotion.php'" value="ยืนยัน">
                    </center>
                </div>
            </div>
        </div>
    </section>
    <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
</body>

</html>
