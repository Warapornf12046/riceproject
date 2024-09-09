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

                // ดึงข้อมูลล่าสุดจากฐานข้อมูล (สินค้า)
                $sqlProduct = "SELECT * FROM orderproduct ORDER BY orderProduct_Id DESC LIMIT 1";
                $resultProduct = mysqli_query($mysqli_p, $sqlProduct);

                // ดึงข้อมูลล่าสุดจากฐานข้อมูล (บริการ)
                $sqlService = "SELECT * FROM orderservice ORDER BY orderService_Id DESC LIMIT 1";
                $resultService = mysqli_query($mysqli_p, $sqlService);

                // ตรวจสอบว่ามีข้อมูลในทั้งสินค้าและบริการหรือไม่
                $hasProductData = mysqli_num_rows($resultProduct) > 0;
                $hasServiceData = mysqli_num_rows($resultService) > 0;

                if ($hasProductData || $hasServiceData) {
                    // ถ้ามีข้อมูลให้แสดงผล
                    if ($hasProductData) {
                        // แสดงรายละเอียดของสินค้า
                        $rowProduct = mysqli_fetch_assoc($resultProduct);
                        echo "<b>สินค้า</b><br>";
                        echo "<div class='summary'>";
                        if ($rowProduct['Kg_Rice_bran']) {
                            echo "รำข้าว  จำนวน: " . $rowProduct['Kg_Rice_bran'] . " kg&nbsp;&nbsp;";
                            echo "ราคารวม: " . ($rowProduct['Kg_Rice_bran'] * $price_per_kg_product1) . " บาท<br>";
                        }
                        if ($rowProduct['Kg_Husk']) {
                            echo "แกลบ  จำนวน: " . $rowProduct['Kg_Husk'] . " kg&nbsp;&nbsp;";
                            echo "ราคารวม: " . ($rowProduct['Kg_Husk'] * $price_per_kg_product2) . " บาท<br>";
                        }
                        if ($rowProduct['Kg_Rice_chunks']) {
                            echo "ข้าวท่อน  จำนวน: " . $rowProduct['Kg_Rice_chunks'] . " kg&nbsp;&nbsp;";
                            echo "ราคารวม: " . ($rowProduct['Kg_Rice_chunks'] * $price_per_kg_product3) . " บาท<br>";
                        }
                        if ($rowProduct['Kg_Broken_rice']) {
                            echo "ข้าวปลาย  จำนวน: " . $rowProduct['Kg_Broken_rice'] . " kg&nbsp;&nbsp;";
                            echo "ราคารวม: " . ($rowProduct['Kg_Broken_rice'] * $price_per_kg_product4) . " บาท<br>";
                        }
                        echo "</Left>";
                        echo "</div>";
                    }

                    if($hasServiceData) {
                        // แสดงรายละเอียดของบริการ
                        $rowService = mysqli_fetch_assoc($resultService);
                        echo "<b>บริการ</b><br>";
                        echo "<div class='summary'>";
                        echo $rowService['ServiceProduct_Name'] . "&nbsp;&nbsp;";
                        echo "จำนวน: " . $rowService['Bucket_Service'] . " ถัง&nbsp;&nbsp;";
                        echo "ราคารวม: " . $rowService['Total_Service'] . " บาท<br>";
                        echo "</Left>";
                        echo "</div><br>";
                    }

                    // คำนวณราคารวมทั้งหมด
                    $totalPriceProduct = ($rowProduct['Kg_Rice_bran'] * $price_per_kg_product1) +
                        ($rowProduct['Kg_Husk'] * $price_per_kg_product2) +
                        ($rowProduct['Kg_Rice_chunks'] * $price_per_kg_product3) +
                        ($rowProduct['Kg_Broken_rice'] * $price_per_kg_product4);

                    $totalPrice = $totalPriceProduct + $rowService['Total_Service'];

                    // แสดงราคารวมทั้งหมดของสินค้าและบริการ
                    echo "<div>";
                    echo "<center>";
                    echo "<b>ราคารวมทั้งหมด: " . $totalPrice . " บาท</b><br>";
                    echo "</center>";
                    echo "</div>"; 
                    // คะแนนที่ได้
                    $earned_points = ceil($totalPrice / 100);

                    // แสดงคะแนนที่ได้
                    echo "<div style='text-align: center;'><b>คะแนนสะสมที่ได้: " . $earned_points . "</b></div>";

                } else {
                    // ถ้าไม่มีข้อมูลเลยให้แสดงข้อความ "ไม่พบข้อมูลสินค้าหรือบริการ"
                    echo "ไม่พบข้อมูลสินค้าหรือบริการ";
                }
                ?>
                <br>
                <div>
                    <center>
                        <input class="btn1" type="button" onclick="document.location='OrderService.php'" value="ย้อนกลับ">
                        <input class="btn" name="receiptsub" type="submid" onclick="document.location='receiptall.php'" value="ยืนยัน">
                    </center>
                </div>
            </div>
        </div>
    </section>
    <p class="time" id="real-time"></p>
    <script src="assets/js/script.js"></script>
</body>

</html>
