<?php
session_start();
include('include/config.php');
if (isset($_POST['selected_member'])) {
    mysqli_query($mysqli_p, "SET NAMES UTF8");

    $memberName = $_POST['selected_member'];

    if (isset($_POST['promotionsum'])) {
        $promotion = $_POST['PRO001'];

        $basepoint = 0;

        switch ($promotion) {
            case "มีสิทธิใช้บริการอบข้าว 1 ครั้ง":
                $basepoint += 1000;
                break;
            case "มีสิทธิ์เลือกใช้บริการอะไรก็ได้ 1 ครั้ง":
                $basepoint += 3000;
                break;
            case "มีสิทธิ์ใช้บริการคัด/ฝัดข้าว 1 ครั้ง":
                $basepoint += 500;
                break;
            default:
                break;
        }

        // ทำการบันทึกข้อมูลในตาราง orderservice
        $sql = "INSERT INTO promotion (Member_Id, Promotion_Name, Promotion_Date)
                VALUES('$memberName', '$promotion', NOW())";

        $q = mysqli_query($mysqli_p, $sql);

        if (!$q) {
            die("Error: " . mysqli_error($mysqli_p));
        }
    }
}

$mysqli_p->close();
?>

<!doctype html>
<html class="no-js" lang="en">
<!-- อยู่ที่ ออเดอร์ดีเทล -->
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

    <section id="contact" class="contact">
        <div class="container">
            <div class="single-contact-box">
            <form action="promotion.php" method="post" onsubmit="return confirm('ยืนยันการส่งข้อมูล ??')">
                    <h2><b><center>รายการโปรโมชั่น</center></b></h2>
                <div class="form-section">
                <div class="radio-group">
                    <label class="radio">
						<input type="radio" name="PRO001" value="มีสิทธิใช้บริการอบข้าว 1 ครั้ง"> สะสมครบ 1,000 แต้ม มีสิทธิใช้บริการอบข้าว 1 ครั้ง
					</label>
                    <label class="radio">
						<input type="radio" name="PRO001" value="มีสิทธิ์เลือกใช้บริการอะไรก็ได้ 1 ครั้ง"> สะสมครบ 3,000 แต้ม มีสิทธิ์เลือกใช้แบบ full option
					</label>
                    <label class="radio">
						<input type="radio" name="PRO001" value="มีสิทธิ์ใช้บริการคัด/ฝัดข้าว 1 ครั้ง"> สะสมครบ 500 แต้ม มีสิทธิ์ใช้บริการคัด/ฝัดข้าว 1 ครั้ง
					</label>
                </div>  
                </div>  
                    <div> <center><br>
                    <input type="hidden" name="selected_member" value="<?php echo $memberName; ?>">
                    <button class="btn" name="promotionsum" type="submit"> ยืนยัน</button>
                    </div></center><br>
                </form>
            </div>
        </div>
        <div class="btn-container">
            <input class="btn1" type="button" onclick="document.location='member3.php'" value="ย้อนกลับ">
            <input class="btn1 " type="button" name="" onclick="document.location='receiptpromotion.php'" value="ใบเสร็จ">
        </div>
    </section>
    <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
</body>

</html>
