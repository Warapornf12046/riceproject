<?php
include('../include/config.php');
// Set charset
mysqli_set_charset($mysqli_p, "utf8");

//สร้าง SQL query สำหรับดึงข้อมูลรวมรายได้จากการ
//ขายสินค้าและการให้บริการของสมาชิก โดยใช้ LEFT JOIN เพื่อรวมข้อมูลจากตาราง member, orderservice, และ orderproduct.
$sql = "SELECT 
            m.Member_Dustrict, 
            SUM(os.Total_Service) AS Total_Service,  /*ผลรวมของ Total_Service (จาก orderservice) เก็บเป็น Total_Service*/
            SUM(op.Total_Product) AS Total_Product /* ผลรวมของ Total_Product (จาก orderproduct) เก็บเป็น Total_Product*/
        FROM 
            member m 
            LEFT JOIN orderservice os ON m.Member_Id = os.Member_Id /*LEFT JOIN ตาราง orderservice (os)โดยใช้คอลัมน์ Member_Idเชื่อมโยงกับตาราง member*/
            LEFT JOIN orderproduct op ON m.Member_Id = op.Member_Id "; /*LEFT JOIN ตาราง orderproduct (op)โดยใช้คอลัมน์ Member_Idเชื่อมโยงกับตาราง member*/

//ตรวจสอบว่ามีการเลือกปีและเดือนหรือไม่ โดยใช้ isset($_GET['year']) และ isset($_GET['month'])
// Check if year and month are selected
if(isset($_GET['year']) && isset($_GET['month'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];

    // If both year and month are selected
    if($year != 'all' && $month != 'all') {
        // Add conditions to SQL query to filter by year and month
        $sql .= " WHERE YEAR(os.OrderService_Date) = '$year' AND MONTH(os.OrderService_Date) = '$month'";
    }
    // If only year is selected
    else if($year != 'all') {
        // Add condition to SQL query to filter by year only
        $sql .= " WHERE YEAR(os.OrderService_Date) = '$year'";
    }
}
//สร้างเงื่อนไขใน SQL query ในการกรองข้อมูลตามปีและเดือนที่เลือก
$sql .= " GROUP BY m.Member_Dustrict;";
//การดึงข้อมูลจากฐานข้อมูลโดยใช้ mysqli_query และนำข้อมูลที่ได้มาเก็บในตัวแปร $data ในรูปแบบของ array.
$result = mysqli_query($mysqli_p, $sql);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array($row['Member_Dustrict'], intval($row['Total_Service']), intval($row['Total_Product']));
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Highcharts Example</title>

    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>

<body>

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>

    <script type="text/javascript">
        // Build the chart
        function buildChart(titleText) {
            // อ่านข้อมูล JSON จาก PHP
            var dataFromPHP = <?php echo json_encode($data); ?>;
            // แปลงข้อมูล JSON เป็น JavaScript object
            var dataObj = JSON.parse(JSON.stringify(dataFromPHP));
//ใช้เก็บค่าปีและเดือนที่ถูกเลือก โดยดึงค่านี้จากพารามิเตอร์ใน URL โดยถ้าไม่มีค่าใน URL จะใช้ค่า 'all' แทน
            var selectedYear = "<?php echo isset($_GET['year']) ? $_GET['year'] : 'all'; ?>";
            var selectedMonth = "<?php echo isset($_GET['month']) ? $_GET['month'] : 'all'; ?>";

// เป็นข้อความที่จะนำไปแสดงผลบนหน้าเว็บ โดยตรวจสอบค่าที่ถูกเลือกว่าเป็น 'all' หรือไม่ ถ้าไม่ใช่ก็จะเพิ่มข้อความ 'ปี' หรือ 'เดือน' 
//นำหน้าด้วยค่าที่เลือกไว้
            var yearText = selectedYear != 'all' ? ' ปี ' + selectedYear : '';
            var monthText = selectedMonth != 'all' ? ' เดือน ' + getMonthName(selectedMonth) : '';
            var finalText = 'กราฟแสดงรายได้รวมการขายสินค้าและการให้บริการ' + yearText + monthText;

//โค้ด JavaScript นี้ใช้งานร่วมกับ Highcharts library เพื่อสร้างกราฟแท่ง (column chart) บนเว็บไซต์ 
//เริ่มต้นการสร้างกราฟแท่งโดยใช้ Highcharts library และกำหนด ID ของ element ที่ต้องการแสดงกราฟลงในพารามิเตอร์ตัวแรก
            Highcharts.chart('container', {
          //กำหนดประเภทของกราฟเป็นแท่ง (column chart)
                chart: {
                    type: 'column'
                },
               //กำหนดชื่อของกราฟ (title) โดยใช้ค่าจากตัวแปร finalText และกำหนดการจัดวางข้อความให้อยู่ตรงกลาง
                title: {
                    text: finalText,
                    align: 'center'
                },
               //กำหนดแกน x ของกราฟโดยใช้ข้อมูลจาก dataObj 
               //ซึ่งเป็นอาร์เรย์ของข้อมูล โดยใช้ฟังก์ชัน map เพื่อดึงข้อมูลตำแหน่งที่ 0 ของแต่ละอาร์เรย์เพื่อใช้เป็น categories ของแกน x
                xAxis: {
                    categories: dataObj.map(item => item[0]),
                    crosshair: true,
                    accessibility: {
                        description: 'Countries'
                    }
                },
                //กำหนดแกน y ของกราฟโดยกำหนดค่าต่ำสุดของแกน y เป็น 0 และกำหนดชื่อของแกน y เป็น 'บาท'
                yAxis: {
                    min: 0,
                    title: {
                        text: 'บาท'
                    }
                },
               //กำหนดรูปแบบของ tooltip ที่แสดงเมื่อนำเมาส์ไปชี้ที่แท่งของกราฟ โดยให้มีคำท้ายว่า ' บาท' เพิ่มอยู่ด้านหลังของค่าที่แสดง
                tooltip: {
                    valueSuffix: ' บาท'
                },
                //กำหนดค่าในการแสดงแท่งของกราฟ เช่น ระยะห่างระหว่างแท่ง (pointPadding) และความหนาของเส้นขอบของแท่ง (borderWidth)
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                //กำหนดข้อมูลที่จะใช้สร้างแต่ละชุดของแท่งในกราฟ โดยใช้ข้อมูลจาก dataObj 
                //ซึ่งเป็นอาร์เรย์ของข้อมูล แต่แต่ละชุดข้อมูลจะมีคำแถวเกี่ยวกับชื่อของชุดข้อมูล (name) 
                //และข้อมูลที่จะนำมาสร้างแท่ง (data)
                series: [{
                        name: 'ยอดรวมการบริการ',
                        data: dataObj.map(item => item[1])
                    },
                    {
                        name: 'ยอดรวมสินค้า',
                        data: dataObj.map(item => item[2])
                    }
                ]
            });
        }
//สร้างฟังก์ชัน JavaScript ที่มีชื่อว่า getMonthName โดยฟังก์ชันนี้จะรับพารามิเตอร์เป็น monthNumber 
//ซึ่งเป็นหมายเลขของเดือน (1-12) ที่ต้องการแปลงเป็นชื่อของเดือนภาษาไทย.
        function getMonthName(monthNumber) {
            //สร้างฟังก์ชัน JavaScript ที่มีชื่อว่า getMonthName โดยฟังก์ชันนี้จะรับพารามิเตอร์เป็น 
            //monthNumber ซึ่งเป็นหมายเลขของเดือน (1-12) ที่ต้องการแปลงเป็นชื่อของเดือนภาษาไทย.
            var monthNames = [
                "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
            ];
            //การคืนค่า (return) ฟังก์ชันจะแปลง monthNumber ที่รับเข้ามาให้เป็นตัวเลขจำนวนเต็ม (integer) 
            //ด้วย parseInt แล้วลบด้วย 1 เพื่อให้ได้ index ที่ถูกต้องในอาร์เรย์ monthNames และสุดท้ายคืนค่าชื่อ
            //ของเดือนภาษาไทยที่มีลำดับตาม monthNumber ที่รับเข้ามา.
            return monthNames[parseInt(monthNumber) - 1];
        }

        // เรียกใช้งานฟังก์ชัน buildChart เพื่อสร้างกราฟ
        buildChart();
    </script>

<div class="Sol-1" style="position: absolute; top: 10px; right: 10px; display: flex; flex-direction: row;">
    <form action="" method="GET" style="display: flex; flex-direction: row;">
        <div class="Col-1" style="margin-right: 10px;">
            <select id="YearSelect" name="year" required>
                <option value="">ปี(ค.ศ.)</option>
                <option value="all">ทุกปี</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
            </select>
        </div>
        <div class="Col-2" style="margin-right: 10px;">
            <select id="MonthSelect" name="month" required>
                <option value="">เดือน</option>
                <option value="all">ทุกเดือน</option>
                <option value="1">มกราคม</option>
                <option value="2">กุมภาพันธ์</option>
                <option value="3">มีนาคม</option>
                <option value="4">เมษายน</option>
                <option value="5">พฤษภาคม</option>
                <option value="6">มิถุนายน</option>
                <option value="7">กรกฎาคม</option>
                <option value="8">สิงหาคม</option>
                <option value="9">กันยายน</option>
                <option value="10">ตุลาคม</option>
                <option value="11">พฤศจิกายน</option>
                <option value="12">ธันวาคม</option>
            </select>
        </div>
        <div class="Col-3">
            <input type="submit" value="Filter">
        </div>
    </form>
</div>


</body>

</html>
