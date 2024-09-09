<?php
session_start();
include('include/config.php');
?>

<?php
if (isset($_POST['selected_member'])) { 
    mysqli_query($mysqli_p, "SET NAMES UTF8");

    $memberName = $_POST['selected_member'];

    if (isset($_POST['orderpro'])) {


        $product1 = $_POST['rice_bran'];
        $product2 = $_POST['Husk'];
        $product3 = $_POST['rice_chunks'];
        $product4 = $_POST['broken_rice'];

        // โปรดักไหนที่ไม่ซื้อโปรดเติม 0 ให้ด้วย
        $totalPrice = ($product1*8) + ($product2*8) + ($product3*5) + ($product4*10);
    
        $sql = "INSERT INTO orderproduct (Member_Id,
                                        Kg_Rice_bran,        
                                        Kg_Husk,
                                        Kg_Rice_chunks,
                                        Kg_Broken_rice,
                                        orderProduct_Date,
                                        total_Product)
        VALUES('$memberName',
            '$product1',
            '$product2',
            '$product3',
            '$product4',
            NOW(),
            '$totalPrice')
            ";

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
    <title>Tech City - สินค้าV.2</title>
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
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>

	<div class="container"><h1 class="text-center">รายละเอียดการซื้อสินค้า</h1></div>
    <div class="containerBox">
    <form action="OrderProduct2.php" method="post" onsubmit="return confirm('ยืนยันการส่งข้อมูล ??')">
		<div class="service_product-details">
			<div class="radio-container">
				<h3><b><center>สินค้า</center></b></h3>
			</div>
			<div class="form-section">
				<div class="radio-group">
					<label class="radio">
						รำข้าว
						<input type="number" name="rice_bran" value="รำข้าว"> กิโลกรัม
					</label>
					<label class="radio">
						แกลบ
						<input type="number" name="Husk" value="แกลบ"> กิโลกรัม
					</label>
					<label class="radio">
						ข้าวท่อน
						<input type="number" name="rice_chunks" value="ข้าวท่อน"> กิโลกรัม
					</label>
					<label class="radio">
						ข้าวปลาย
						<input type="number" name="broken_rice" value="ข้าวปลาย"> กิโลกรัม
					</label>
				</div>
                <input type="hidden" name="selected_member" value="<?php echo $memberName; ?>">
                <div class="btn-container">	
                    <button class="btn" name="orderpro" type="submit"> ยืนยัน</button>
                </div>
			</div>
		</div>
	</div>
    <div>
    <div>
        <center>
        <input class="btn1" type="button" onclick="document.location='OrderService.php'" value="ย้อนกลับ">
        <input class="btn1" type="button" onclick="document.location='sumproduct.php'" value="สรุปรายการ">
		</center>
    </div>
    </form>
</body>
</html>
<p class="time" id="real-time"></p>
<script src="assets/js/script.js"></script>
