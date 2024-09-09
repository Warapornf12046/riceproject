<!DOCTYPE html>
<html lang="th">

<head>
    <title>Tech City</title>
    <link rel="stylesheet" href="assets/css/receipt.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link href="img\TechTeam.png" rel="icon">
</head>

<body>
    <div class="container">
        <div class="header">
            <img onclick="document.location='index.html'" src="img/TechTeam.png" alt="Logo" class="logo">
            <h1 class="company-name">TECH TEAM</h1>
            <p class="company-address">
                25 ม.4 ต. ทะนง อ.โพทะเล จ.พิจิตร
            </p>
        </div>

        <?php
        // เชื่อมต่อกับฐานข้อมูล
        include('include/config.php');

        // ตรวจสอบว่ามีการส่งค่า ID ของลูกค้ามาหรือไม่
        $sql_member = "SELECT member.Member_Name, member.Member_Tel ,member.Member_Id
        FROM member 
        JOIN orderservice ON member.Member_Id = orderservice.Member_Id
        ORDER BY orderservice.OrderService_Id DESC 
        LIMIT 1";

        $result_member = $mysqli_p->query($sql_member);
        if ($result_member !== false && $result_member->num_rows > 0) {
            $row_member = $result_member->fetch_assoc();
            echo "<div class='customer-info'>";
            echo "<h2 class='customer-name'>" . $row_member["Member_Name"] . "</h2>";
            echo "<p class='customer-phone'>เบอร์โทรศัพท์: " . $row_member["Member_Tel"] . "</p>";
            echo "<span id='current-date'></span>";
            echo "</div>";
}
        // แสดงข้อมูลจากตาราง orderservice
        $sql_service = "SELECT ServiceProduct_Name, Total_Service, Bucket_Service FROM orderservice ORDER BY orderService_Id DESC LIMIT 1";
        $result_service = $mysqli_p->query($sql_service);

        // กำหนดราคาต่อถังเริ่มต้น
        $pricePerBucket = 0;
        $total_price_all_service = 0;

        // แสดงข้อมูลจากตาราง orderservice
        if ($result_service !== false && $result_service->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>รายการ</th>";
            echo "<th>จำนวน(ถัง)</th>";
            echo "<th>ราคาต่อหน่วย</th>";
            echo "<th>ราคา</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            while ($row_service = $result_service->fetch_assoc()) {
                // กำหนดราคาต่อถังตามประเภทของบริการ
                $serviceType = $row_service["ServiceProduct_Name"];
                switch ($serviceType) {
                    case "สีข้าว":
                        $pricePerBucket += 0;
                        break;
                    case "คัด/ฝัดเมล็ดข้าว":
                        $pricePerBucket += 3;
                        break;
                    case "อบข้าว":
                        $pricePerBucket += 10;
                        break;
                    case "สีข้าว และ คัด/ฝัดเมล็ดข้าว":
                        $pricePerBucket += 3;
                        break;
                    case "สีข้าว และ อบข้าว":
                        $pricePerBucket += 10;
                        break;
                    case "คัด/ฝัดเมล็ดข้าว และ อบข้าว":
                        $pricePerBucket += 13;
                        break;
                    case "สีข้าว และ คัด/ฝัดเมล็ดข้าว และ อบข้าว":
                        $pricePerBucket += 13;
                        break;
                    default:
                        break;
                }

                echo "<tr>";
                echo "<td>" . $row_service["ServiceProduct_Name"] . "</td>";
                echo "<td>" . $row_service["Bucket_Service"] . "</td>";
                echo "<td>" . $pricePerBucket . "</td>";
                echo "<td>" . ($pricePerBucket * $row_service["Bucket_Service"]) . "</td>";
                echo "</tr>";

                // เพิ่มราคารวมทั้งหมดของบริการ
                $total_price_all_service += ($pricePerBucket * $row_service["Bucket_Service"]);
            }

            echo "<tr>";
            echo "<td colspan='3' style='text-align: right;'>ราคารวมทั้งหมด (บริการ):</td>";
            echo "<td>" . $total_price_all_service . " บาท</td>";
            echo "</tr>";

            echo "</tbody>";
            echo "</table>";
        }

        // แสดงข้อมูลจากตาราง orderproduct
        $sql_product = "SELECT * FROM orderproduct ORDER BY orderProduct_Id DESC LIMIT 1";
        $result_product = $mysqli_p->query($sql_product);

        // กำหนดราคาต่อกิโลกรัมของแต่ละสินค้า
        $price_per_kg_product1 = 8;
        $price_per_kg_product2 = 8;
        $price_per_kg_product3 = 5;
        $price_per_kg_product4 = 10;

        // แสดงข้อมูลจากตาราง orderproduct
        if ($result_product !== false && $result_product->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>รายการ</th>";
            echo "<th>จำนวน(กิโลกรัม)</th>";
            echo "<th>ราคาต่อหน่วย</th>";
            echo "<th>ราคา</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            $row_product = $result_product->fetch_assoc();

            // คำนวณราคารวมแต่ละรายการของสินค้า
            $total_price_product = 0;

            if ($row_product['Kg_Rice_bran']) {
                $kg_product1_price = $row_product['Kg_Rice_bran'] * $price_per_kg_product1;
                $total_price_product += $kg_product1_price;
                echo "<tr>";
                echo "<td>รำ</td>";
                echo "<td>" . $row_product['Kg_Rice_bran'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product1 . "</td>";
                echo "<td>" . $kg_product1_price . " บาท</td>";
                echo "</tr>";
            }

            if ($row_product['Kg_Husk']) {
                $kg_product2_price = $row_product['Kg_Husk'] * $price_per_kg_product2;
                $total_price_product += $kg_product2_price;
                echo "<tr>";
                echo "<td>แกลบ</td>";
                echo "<td>" . $row_product['Kg_Husk'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product2 . "</td>";
                echo "<td>" . $kg_product2_price . " บาท</td>";
                echo "</tr>";
            }

            if ($row_product['Kg_Rice_chunks']) {
                $kg_product3_price = $row_product['Kg_Rice_chunks'] * $price_per_kg_product3;
                $total_price_product += $kg_product3_price;
                echo "<tr>";
                echo "<td>ข้าวท่อน</td>";
                echo "<td>" . $row_product['Kg_Rice_chunks'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product3 . "</td>";
                echo "<td>" . $kg_product3_price . " บาท</td>";
                echo "</tr>";
            }

            if ($row_product['Kg_Broken_rice']) {
                $kg_product4_price = $row_product['Kg_Broken_rice'] * $price_per_kg_product4;
                $total_price_product += $kg_product4_price;
                echo "<tr>";
                echo "<td>ข้าวปลาย</td>";
                echo "<td>" . $row_product['Kg_Broken_rice'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product4 . "</td>";
                echo "<td>" . $kg_product4_price . " บาท</td>";
                echo "</tr>";
            }
            // แสดงราคารวมทั้งหมดของสินค้า
            echo "<tr>";
            echo "<td colspan='3' style='text-align: right;'>ราคารวมทั้งหมด (สินค้า):</td>";
            echo "<td>" . $total_price_product . " บาท</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";

        }

        // แสดงราคารวมทั้งหมด
        echo "<tr>";
        echo "<td colspan='3' style='text-align: right;'>ราคารวมทั้งหมด:</td>";
        echo "<td style='text-align: right;'>" . ($total_price_all_service + $total_price_product) . " บาท</td>";
        echo "</tr>";

        // คำนวณคะแนนที่ได้จากการซื้อ
        $earned_points = ceil(($total_price_all_service + $total_price_product) / 100);

        /// ดึงคะแนนสะสมของสมาชิกจากฐานข้อมูล
        if(isset($row_member["Member_Id"])){
            $sql_member_points = "SELECT Member_Point FROM member WHERE Member_Id = " . $row_member["Member_Id"];
            $result_member_points = $mysqli_p->query($sql_member_points);

            if ($result_member_points !== false && $result_member_points->num_rows > 0) {
                $row_points = $result_member_points->fetch_assoc();
                $member_points = $row_points['Member_Point'];
            } else {
                $member_points = 0;
            }

            // คำนวณคะแนนที่ได้จากการซื้อ
            $earned_points = ceil(($total_price_all_service + $total_price_product) / 100);

            // คะแนนสะสมที่ใหม่
            $new_points_balance = $member_points + $earned_points;

            // แสดงคะแนนที่เพิ่มและคะแนนสะสมทั้งหมดในใบเสร็จ
            echo "<div class='member-points'>";
            echo "<p>คะแนนที่เพิ่ม: +" . $earned_points . " คะแนน</p>";
            echo "<p>คะแนนสะสมทั้งหมด: " . $new_points_balance . " คะแนน</p>";
            echo "</div>";

            // อัปเดตคะแนนสะสมของสมาชิกในฐานข้อมูล
            $sql_update_points = "UPDATE member SET Member_Point = $new_points_balance WHERE Member_Id = " . $row_member["Member_Id"];
            $mysqli_p->query($sql_update_points);
        }
        ?>
        <div class="footer">
            <p>ขอบคุณที่ใช้บริการ</p>
        </div>

        <!-- ปุ่มสำหรับพิมพ์ใบเสร็จ -->
        <input type="button" class="print-button" value="พิมพ์ใบเสร็จ" onclick="javascript:this.style.display='none';window.print();">
        <br><br>
    </div>

    <script src="assets/js/receipt.js"></script>
</body>

</html>