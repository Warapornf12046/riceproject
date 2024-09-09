<?php
session_start();
include('include\config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // กำหนด Username และ Password ที่ถูกต้อง
    $correct_username = "owner";
    $correct_password = "12345678";

    // รับข้อมูลที่ผู้ใช้ป้อนผ่านฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบว่าชื่อผู้ใช้และรหัสผ่านถูกต้องหรือไม่
    if ($username == $correct_username && $password == $correct_password) {
        // ถ้าถูกต้อง สร้าง session และเปลี่ยนเส้นทางไปยังหน้าที่ต้องการ
        $_SESSION['username'] = $username;
        header("Location: owner/index.php"); // ส่งไปยังหน้า owner/index.html
        exit;
    } else {
        // ถ้าไม่ถูกต้อง ให้แสดงข้อความผิดพลาด
        $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tech City</title>
    <link rel="stylesheet" href="assets\css\loginstyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link href="img\TechTeam.png" rel="icon">
</head>
<body>

  <div class="login-container">
    <center><img src="img\TechTeam.png" alt="Tech Team Logo" width="100" height="100"></center>
    <center><h2>เข้าสู่ระบบ</h2></center>
    <form method="post">
      <label for="username">Owner name:</label>
      <input type="text" id="username" name="username" required><br><br>

      <label for="password">Password:</label> &nbsp; &nbsp; 
      <input type="password" id="password" name="password" required><br><br>

      <?php if(isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
      <?php } ?>

      <center>
        <input class="button" type="submit" onclick="document.location='index.html'" value="ย้อนกลับ">
        <button type="submit" class="button" name="login">เข้าสู่ระบบ</button>
        

      </center>
    </form>
    
  </div>

</body>
</html>
