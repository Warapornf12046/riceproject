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

        // ส่งคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง member
        $sql_member = "SELECT orderservice.*, member.Member_Name, member.Member_Tel
               FROM orderservice
               JOIN member ON orderservice.Member_Id = member.Member_Id
               ORDER BY orderservice.orderService_Id DESC
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
        } else {
            echo "No member found";
        }
    ?>

    <table class="table">
      <thead>
        <tr>
          <th>รายการ</th>
          <th>จำนวน(ถัง)</th>
          <th>ราคาต่อถัง</th>
          <th>ราคา</th>
        </tr>
      </thead>

      <tbody>

      
      <?php
      // กำหนดราคาต่อถังเริ่มต้น
      $pricePerBucket = 0;

      // ดึงข้อมูลจากตาราง orderservice
      $sql = "SELECT ServiceProduct_Name, Total_Service, Bucket_Service FROM orderservice ORDER BY orderService_Id DESC LIMIT 1";
      $result = $mysqli_p->query($sql);
      $total_price_all=0;
      // แสดงข้อมูลจากตาราง orderservice
      if ($result !== false && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // กำหนดราคาต่อถังตามประเภทของบริการ
              $serviceType = $row["ServiceProduct_Name"];
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

              // แสดงข้อมูลในตาราง
              echo "<tr>";
              echo "<td>" . $row["ServiceProduct_Name"] . "</td>";
              echo "<td>" . $row["Bucket_Service"] . "</td>";
              echo "<td>" . $pricePerBucket . "</td>";
              echo "<td>" . ($pricePerBucket * $row["Bucket_Service"]) . "</td>";
              echo "</tr>";

              // เพิ่มราคารวมทั้งหมด
              $total_price_all += ($pricePerBucket * $row["Bucket_Service"]);

              // แสดงราคารวมทั้งหมด
              echo "<tr>";
              echo "<td colspan='3' style='text-align: right;'>ราคารวมทั้งหมด:</td>";
              echo "<td>" . $total_price_all. " บาท</td>";
              echo "</tr>";

              // คำนวณคะแนนที่ได้จากการซื้อ
              $earned_points = ceil($total_price_all / 100);

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
          }
      } 
      else {
          echo "No results found";
      }
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

  <!-- กด Logo ถ้าต้องการยกเลิก หรือ กลับไปหน้าอื่น -->
  <script src="assets/js/receipt
