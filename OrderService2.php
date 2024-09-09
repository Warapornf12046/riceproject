<?php
session_start();
include('include/config.php');

if (isset($_POST['selected_member'])) {
    mysqli_query($mysqli_p, "SET NAMES UTF8");

    $memberName = $_POST['selected_member'];

    if (isset($_POST['orderser'])) {
        $servise = $_POST['SP001'];
        $riceweigth = $_POST['Rice_weight'];

        $basePricePerKg = 0;

        switch ($servise) {
            case "สีข้าว":
                $basePricePerKg += 0;
                break;
            case "คัด/ฝัดเมล็ดข้าว":
                $basePricePerKg += 3;
                break;
            case "อบข้าว":
                $basePricePerKg += 10;
                break;
            case "สีข้าว และ คัด/ฝัดเมล็ดข้าว":
                $basePricePerKg += 3;
                break;
            case "สีข้าว และ อบข้าว":
                $basePricePerKg += 10;
                break;
            case "คัด/ฝัดเมล็ดข้าว และ อบข้าว":
                $basePricePerKg += 13;
                break;
            case "สีข้าว และ คัด/ฝัดเมล็ดข้าว และ อบข้าว":
                $basePricePerKg += 13;
                break;
            default:
                break;
        }

        $totalPrice = $riceweigth * $basePricePerKg;

        // ทำการบันทึกข้อมูลในตาราง orderservice
        $sql = "INSERT INTO orderservice (Member_Id, Bucket_Service, ServiceProduct_Name, OrderService_Date, total_Service)
                VALUES('$memberName', '$riceweigth', '$servise', NOW(), '$totalPrice')";

        $q = mysqli_query($mysqli_p, $sql);

        if (!$q) {
            die("Error: " . mysqli_error($mysqli_p));
        }
    }
}

$mysqli_p->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tech City - บริการ</title>
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
            <a href="OrderService.php">บริการและสินค้า</a>
            <a href="OrderProduct2.php">สินค้า</a>
            <a href="point.php">คะแนนสะสม</a>
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>

    <div class="container">
        <h1 class="text-center">รายละเอียดการใช้บริการ</h1>
    </div>

    <div class="containerBox">
        <div class="service_product-details">
            <div class="checkbox-container">
                <div class="radio-container">
                    <h3><b><center>บริการ</center></b></h3>
                </div>
            </div><br>

            <form action="OrderService.php" method="post" onsubmit="return confirm('ยืนยันการส่งข้อมูล ??')">
                น้ำหนักข้าว : &nbsp;
                <input type="number" name="Rice_weight">ถัง
                <div class="form-section">
                    <div class="radio-group">
                        <label class="radio">
                            <input type="radio" name="SP001" value="สีข้าว"> สีข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="คัด/ฝัดเมล็ดข้าว"> คัด/ฝัดเมล็ดข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="อบข้าว"> อบข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="สีข้าว และ คัด/ฝัดเมล็ดข้าว"> สีข้าว และ คัด/ฝัดเมล็ดข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="สีข้าว และ อบข้าว"> สีข้าว และ อบข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="คัด/ฝัดเมล็ดข้าว และ อบข้าว"> คัด/ฝัดเมล็ดข้าว และ อบข้าว
                        </label>
                        <label class="radio">
                            <input type="radio" name="SP001" value="สีข้าว และ คัด/ฝัดเมล็ดข้าว และ อบข้าว"> สีข้าว และ คัด/ฝัดเมล็ดข้าว และ อบข้าว
                        </label>
                    </div>
                </div>
                <input type="hidden" name="selected_member" value="<?php echo $memberName; ?>">
                    <button class="btn" name="orderser" type="submit"> ยืนยัน</button>
            </form>
        </div>
    </div>
    <div> <center>
            <input class="btn" type="button" onclick="document.location='index.html'" value="ย้อนกลับ">
            <input class="btn" type="button" onclick="document.location='sumservicepromotion.php'" value="สรุปรายการ">
    </div></center>
    <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
</body>

</html>
