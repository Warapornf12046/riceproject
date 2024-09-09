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

        // ส่งคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง orderproduct
        $sql_member = "SELECT orderproduct.*, member.Member_Name, member.Member_Tel
               FROM orderproduct
               JOIN member ON orderproduct.Member_Id = member.Member_Id
               ORDER BY orderproduct.OrderProduct_Id DESC
               LIMIT 1";
        $result_member = $mysqli_p->query($sql_member);

        // แสดงข้อมูลจากตาราง member
        if ($result_member !== false && $result_member->num_rows > 0) {
          $row_member = $result_member->fetch_assoc();
          echo "<div class='customer-info'>";
          echo "<h2 class='customer-name'>" . $row_member["Member_Name"] . "</h2>";
          echo "<p class='customer-phone'>เบอร์โทรศัพท์: " . $row_member["Member_Tel"] . "</p>";
          echo "<span id='current-date'></span>";
          echo "</div>";
        }
    ?>

    <table class="table">
      <thead>
        <tr>
          <th>รายการ</th>
          <th>จำนวน(กิโลกรัม)</th>
          <th>ราคาต่อกิโลกรัม</th>
          <th>ราคา</th>
        </tr>
      </thead>

      <tbody>

      <?php
        // กำหนดราคาต่อกิโลกรัมของแต่ละสินค้า
        $price_per_kg_product1 = 8;
        $price_per_kg_product2 = 8;
        $price_per_kg_product3 = 5;
        $price_per_kg_product4 = 10;

        // ดึงข้อมูลล่าสุดจากตาราง orderproduct
        $sqlProduct = "SELECT * FROM orderproduct ORDER BY orderProduct_Id DESC LIMIT 1";
        $resultProduct = mysqli_query($mysqli_p, $sqlProduct);

        // ตรวจสอบว่ามีข้อมูลใน orderproduct หรือไม่
        $hasProductData = mysqli_num_rows($resultProduct) > 0;

        if ($hasProductData) {
            $rowProduct = mysqli_fetch_assoc($resultProduct);

            // คำนวณราคารวมแต่ละรายการของสินค้าและรวมเข้ากับราคารวมทั้งหมด
            $total_price = 0;

            // แสดงข้อมูลสินค้า
            if ($rowProduct['Kg_Rice_bran']) {
                $kg_product1_price = $rowProduct['Kg_Rice_bran'] * $price_per_kg_product1;
                $total_price += $kg_product1_price;
                echo "<tr>";
                echo "<td>รำข้าว</td>";
                echo "<td>" . $rowProduct['Kg_Rice_bran'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product1 . "</td>";
                echo "<td>" . $kg_product1_price . " บาท</td>";
                echo "</tr>";
            }

            if ($rowProduct['Kg_Husk']) {
                $kg_product2_price = $rowProduct['Kg_Husk'] * $price_per_kg_product2;
                $total_price += $kg_product2_price;
                echo "<tr>";
                echo "<td>แกลบ</td>";
                echo "<td>" . $rowProduct['Kg_Husk'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product2 . "</td>";
                echo "<td>" . $kg_product2_price . " บาท</td>";
                echo "</tr>";
            }

            if ($rowProduct['Kg_Rice_chunks']) {
                $kg_product3_price = $rowProduct['Kg_Rice_chunks'] * $price_per_kg_product3;
                $total_price += $kg_product3_price;
                echo "<tr>";
                echo "<td>ข้าวท่อน</td>";
                echo "<td>" . $rowProduct['Kg_Rice_chunks'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product3 . "</td>";
                echo "<td>" . $kg_product3_price . " บาท</td>";
                echo "</tr>";
            }

            if ($rowProduct['Kg_Broken_rice']) {
                $kg_product4_price = $rowProduct['Kg_Broken_rice'] * $price_per_kg_product4;
                $total_price += $kg_product4_price;
                echo "<tr>";
                echo "<td>ข้าวปลาย</td>";
                echo "<td>" . $rowProduct['Kg_Broken_rice'] . " กิโลกรัม</td>";
                echo "<td>" . $price_per_kg_product4 . "</td>";
                echo "<td>" . $kg_product4_price . " บาท</td>";
                echo "</tr>";
            }

            // แสดงราคารวมทั้งหมด
            echo "<tr>";
            echo "<td colspan='3' style='text-align: right;'>ราคารวมทั้งหมด:</td>";
            echo "<td>" . $total_price . " บาท</td>";
            echo "</tr>";
        } 
        
            // คำนวณคะแนนที่ได้จากการซื้อ
            $earned_points = ceil($total_price / 100);

            // ดึงคะแนนสะสมของสมาชิกจากฐานข้อมูล
            $sql_member_points = "SELECT  Member_Point FROM member WHERE Member_Id = " . $row_member["Member_Id"];
            $result_member_points = $mysqli_p->query($sql_member_points);

            if ($result_member_points !== false && $result_member_points->num_rows > 0) {
                $row_points = $result_member_points->fetch_assoc();
                $member_points = $row_points['Member_Point'];
            } else {
                $member_points = 0;
            }

            // คะแนนที่ใช้
            $used_points = 0;

            // ดึงคะแนนที่ใช้จากตาราง Member
            $sql_used_points = "SELECT Member_PointUse FROM member WHERE Member_Id = " . $row_member["Member_Id"];
            $result_used_points = $mysqli_p->query($sql_used_points);

            if ($result_used_points !== false && $result_used_points->num_rows > 0) {
                $row_used_points = $result_used_points->fetch_assoc();
                $used_points = $row_used_points['Member_PointUse'];
            }

            // อัปเดตคะแนนสะสมของสมาชิก
            $new_points_balance = $member_points + $earned_points;

            // อัปเดตคะแนนสะสมของสมาชิกในฐานข้อมูล
            $sql_update_points = "UPDATE member SET Member_Point= $new_points_balance WHERE Member_Id = " . $row_member["Member_Id"];
            $mysqli_p->query($sql_update_points);
      ?>
      </tbody>
    </table>

    <!-- แสดงคะแนนที่เพิ่ม, คะแนนที่ใช้, และคะแนนสะสมทั้งหมด -->
    <div class="member-points">
        <p>คะแนนที่เพิ่ม: +<?php echo $earned_points; ?> คะแนน</p>
        <p>คะแนนที่ใช้: <?php echo $used_points; ?> คะแนน</p>
        <p>คะแนนสะสมทั้งหมด: <?php echo $new_points_balance; ?> คะแนน</p>
    </div>

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