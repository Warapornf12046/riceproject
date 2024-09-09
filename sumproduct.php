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
                // เชื่อมต่อกับฐานข้อมูล
                include('include/config.php');

                // กำหนดราคาต่อกิโลกรัมของแต่ละสินค้า
                $price_per_kg_product1 = 8;
                $price_per_kg_product2 = 8;
                $price_per_kg_product3 = 5;
                $price_per_kg_product4 = 10;

                // กำหนดตัวแปรราคารวมทั้งหมดของสินค้า
                $total_price = 0;

                // ดึงข้อมูลล่าสุดจากฐานข้อมูล (สินค้า)
                $sqlProduct = "SELECT * FROM orderproduct ORDER BY orderProduct_Id DESC LIMIT 1";
                $resultProduct = mysqli_query($mysqli_p, $sqlProduct);

                // ตรวจสอบว่ามีข้อมูลในสินค้าหรือไม่
                $hasProductData = mysqli_num_rows($resultProduct) > 0;

                if ($hasProductData) { // ถ้ามีข้อมูลสินค้าอย่างเดียว
                    $rowProduct = mysqli_fetch_assoc($resultProduct);

                    // คำนวณราคารวมแต่ละรายการของสินค้าและรวมเข้ากับราคารวมทั้งหมด
                    if ($rowProduct['Kg_Rice_bran']) {
                        $kg_product1_price = $rowProduct['Kg_Rice_bran'] * $price_per_kg_product1;
                        $total_price += $kg_product1_price;
                        echo "<div class='item' style='text-align: left; padding-right: 15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รำข้าว  จำนวน: " . $rowProduct['Kg_Rice_bran'] . " kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
                        echo "ราคารวม: " . $kg_product1_price . " บาท</div>";
                    }
                    if ($rowProduct['Kg_Husk']) {
                        $kg_product2_price = $rowProduct['Kg_Husk'] * $price_per_kg_product2;
                        $total_price += $kg_product2_price;
                        echo "<div class='item' style='text-align: left; padding-right: 15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แกลบ  จำนวน: " . $rowProduct['Kg_Husk'] . " kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "ราคารวม: " . $kg_product2_price . " บาท</div>";
                    }
                    if ($rowProduct['Kg_Rice_chunks']) {
                        $kg_product3_price = $rowProduct['Kg_Rice_chunks'] * $price_per_kg_product3;
                        $total_price += $kg_product3_price;
                        echo "<div class='item' style='text-align: left; padding-right: 15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าวท่อน  จำนวน: " . $rowProduct['Kg_Rice_chunks'] . " kg&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "ราคารวม: " . $kg_product3_price . " บาท</div>";
                    }
                    if ($rowProduct['Kg_Broken_rice']) {
                        $kg_product4_price = $rowProduct['Kg_Broken_rice'] * $price_per_kg_product4;
                        $total_price += $kg_product4_price;
                        echo "<div class='item' style='text-align: left; padding-right: 15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าวปลาย  จำนวน: " . $rowProduct['Kg_Broken_rice'] . " kg&nbsp;&nbsp;&nbsp;";
                        echo "ราคารวม: " . $kg_product4_price . " บาท</div><br>";
                    }
                    // ราคารวมทั้งหมด
                    $total_price = $rowProduct['Total_Product'];

                    // คำนวณคะแนนที่ได้
                    $earned_points = ceil($total_price / 100);

                    // ไอดีของสมาชิกโดยใช้ Member_Id
                    $member_id = isset($_SESSION['Member_Id']) ? $_SESSION['Member_Id'] : 0; 

                    // อัปเดตคะแนนสะสมของสมาชิก
                    $sqlUpdatePoints = "UPDATE member SET Member_Point = Member_Point + $earned_points WHERE Member_Id = $member_id";
                    $resultUpdatePoints = mysqli_query($mysqli_p, $sqlUpdatePoints);

                    // ตรวจสอบว่ามีข้อมูลถูกอัปเดตหรือไม่
                    if ($resultUpdatePoints) {
                        echo "<center><b>คะแนนสะสมที่ได้: $earned_points</b></center>";
                    } else {
                        echo "<div class='summary'>มีข้อผิดพลาดในการเพิ่มคะแนนสะสม</div>";
                    }
                } 
                else { // ถ้าไม่มีข้อมูลเลย
                    // แสดงข้อความ "ไม่พบข้อมูลสินค้าหรือบริการ"
                    echo "<div class='summary'>ไม่พบข้อมูลสินค้าหรือบริการ</div>";
                }
                // แสดงราคารวมทั้งหมด
                echo "<center><b>ราคารวมทั้งหมด: " . $total_price . " บาท</b></center>";
                ?>
                <br>
                <div>
                    <center>
                        <input class="btn1 " type="button" onclick="document.location='OrderProduct.php'" value="ย้อนกลับ">
                        <input class="btn" name="receiptsub" type="submid" onclick="document.location='receiptproduct.php'" value="ยืนยัน">
                    </center>
                </div>
            </div>
        </div>
    </section>
    <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
</body>

</html>
