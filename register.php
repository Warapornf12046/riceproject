<?php
session_start();
include('include/config.php');
if (isset($_POST['Confirm'])) {

    mysqli_query($mysqli_p, "SET NAMES UTF8");

    $Name = $_POST['Name'];
    $Phone_Number = $_POST['Phone_Number'];
    $Home_Number = $_POST['Home_Number'];
    $District = $_POST['District'];
    $Subdistrict = $_POST['Subdistrict'];
    $Province = $_POST['Province'];
    
    $sql = "INSERT INTO member (Member_Name,        
                                Member_Tel,
                                Member_Address,
                                Member_Subdistrict,
                                Member_Dustrict,
                                Member_Province,
                                Member_Since)
    VALUES('$Name',
        '$Phone_Number',
        '$Home_Number',
        '$District',
        '$Subdistrict',
        '$Province',
        NOW())";

    $q = mysqli_query($mysqli_p, $sql);
    if (!$q) {
        die("Error: " . mysqli_error($mysqli_p));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tech City</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
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
    </div>
    <main>
        <div class="container">
            <div class="single-contact-box">
                <h2>
                    <center>สมัครสมาชิก</center>
                </h2>
                <form action="register.php" method="post" onsubmit="return confirm('สมัครสมาชิกสำเร็จแล้ว!!')">
                    <label for="name">ชื่อ </label>
                    <input type="text" id="name" name="Name" placeholder="กรอก ชื่อ-นามสกุล" required>
                    <label for="phone">เบอร์โทรศัพท์ </label>
                    <input type="tel" id="phone" name="Phone_Number" placeholder="xxxxxxxxxx" required>

                    <label for="Address"> ที่อยู่ </label>
                    <div>
                        <label>บ้านเลขที่:</label>
                        <input type="text" name="Home_Number" placeholder="ตัวอย่าง xx/x ม.x" required>
                        
                        <label>อำเภอ:</label>
                        <select name="Subdistrict" id="subdistrictSelect" required>
                            <option value="เลือกอำเภอ"></option>
                            <option value="เมืองพิจิตร">เมือง</option>
                            <option value="วังทรายพูน">วังทรายพูน</option>
                            <option value="โพธิ์ประทับช้าง">โพธิ์ประทับช้าง</option>
                            <option value="ตะพานหิน">ตะพานหิน</option>
                            <option value="บางมูลนาก">บางมูลนาก</option>
                            <option value="โพทะเล">โพทะเล</option>
                            <option value="สามง่าม">สามง่าม</option>
                            <option value="ทับคล้อ">ทับคล้อ</option>
                            <option value="สากเหล็ก">สากเหล็ก</option>
                            <option value="บึงนาราง">บึงนาราง</option>
                            <option value="ดงเจริญ">ดงเจริญ</option>
                            <option value="วชิรบารมี">วชิรบารมี</option>
                        </select>
                        
                    </div>
                    <div>
                    <label>ตำบล:</label>
                        <select name="District" id="districtSelect" required disabled>
                            <script src="assets/js/district.js"></script>
                        </select>&nbsp;&nbsp;
                        <label>จังหวัด:</label>
                        <input type="text" name="Province" value="พิจิตร">
                    </div>
                    <br>
                    <button class="btn1" type="submit" name="Confirm"> ยืนยัน</button>
                </form>
            </div>
        </div>
    </main>
</body>
<p class="time" id="real-time"></p>
<script src="assets/js/script.js"></script>
</html>