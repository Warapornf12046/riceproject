<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>กราฟสรุปการใช้บริการทั้งหมด</title>

    <style type="text/css">
         /* กำหนดขนาดของตารางและกราฟให้มีความกว้างระหว่าง 320px ถึง 660px และจัดกึ่งกลางในหน้าจอ */
        .highcharts-figure, 
        .highcharts-data-table table { /* กำหนดสไตล์สำหรับตารางข้อมูลที่ถูกสร้างขึ้นโดย Highcharts */
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }
/* กำหนดสไตล์สำหรับตารางข้อมูลที่ถูกสร้างขึ้นโดย Highcharts */
        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
/* กำหนดสไตล์สำหรับแท็ก <caption> ในตารางข้อมูล */
        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }
/*กำหนดสไตล์สำหรับเซลล์หัวของตาราง*/
        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }
/*กำหนดสไตล์สำหรับเซลล์ข้อมูลและ caption ในตาราง*/
        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }
/*กำหนดสไตล์สำหรับแถวหัวของตารางและแถวที่เป็นแถวคู่ของตาราง*/
        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }
/*กำหนดสไตล์สำหรับแถวเมื่อเมาส์ชี้ไป*/
        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>
<body>
<script src="https://code.highcharts.com/highcharts.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
</figure>

<?php
// Connect to the database
include('../include/config.php');
// Set charset
mysqli_set_charset($mysqli_p, "utf8");
// SQL query นี้ดึงข้อมูลชื่อสินค้า/บริการ พร้อมกับจำนวนการใช้งาน จากตาราง orderserviceโดย JOIN กับตาราง serviceandproduct
$sql = "SELECT sp.ServiceProduct_Name, COUNT(os.OrderService_Id) AS Usage_Count 
        FROM orderservice os 
        INNER JOIN serviceandproduct sp ON os.ServiceProduct_Name = sp.ServiceProduct_Name ";

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

// เพิ่ม GROUP BY clause ลงใน SQL query เรียงลำดับผลลัพธ์ตาม sp.ServiceProduct_Name (ชื่อสินค้า/บริการ)
$sql .= " GROUP BY sp.ServiceProduct_Name";

$result = mysqli_query($mysqli_p, $sql);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {  //เพื่อดึงข้อมูลจากผลลัพธ์ที่ได้จากการ query และนำมาใส่ในตัวแปร $row ในแต่ละรอบของลูป while
    //นำข้อมูลที่ได้จากแต่ละรอบของลูป while เก็บในตัวแปร $data ในรูปแบบ array โดยกำหนด key 'name' 
    //ให้เป็นชื่อสินค้าหรือบริการที่ได้จากฐานข้อมูล และ key 'y' ให้เป็นค่าจำนวนการใช้งานของสินค้าหรือบริการนั้น ๆ 
    //โดยใช้ฟังก์ชัน intval() เพื่อแปลงค่าให้อยู่ในรูปแบบตัวเลขจำนวนเต็มในกรณีที่ข้อมูลเป็นข้อความที่อยู่ในรูปแบบตัวเลข
    $data[] = array('name' => $row['ServiceProduct_Name'], 'y' => intval($row['Usage_Count']));
}
// Encode data to JSON format
$data2 = json_encode($data);
?>

<script type="text/javascript">
    // Build the chart
    function buildChart(titleText) {
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: titleText,
                align: 'left'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true, // เปิดใช้งานการแสดงข้อมูลบนแผนภูมิ
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%' // รูปแบบการแสดงข้อมูลที่ต้องการเป็นเปอร์เซ็นต์
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'จำนวนสินค้า',
                colorByPoint: true,
                data: <?php echo $data2; ?>
            }]
        });
    }

    // Get selected year and month from URL parameters
    //ดึงค่าของ selectedYear และ selectedMonth จาก URL query string และเก็บไว้ในตัวแปรเพื่อใช้งาน
    var urlParams = new URLSearchParams(window.location.search);
    var selectedYear = urlParams.get('year');
    var selectedMonth = urlParams.get('month');

    // Set initial title based on selected year and month
    var titleText = 'กราฟสรุปการใช้บริการทั้งหมด';
    /*เช็คเงื่อนไขว่า selectedYear ไม่เป็น null, ไม่เป็นสตริงว่าง, และไม่มีค่าเท่ากับ 'all' โดยถ้าเงื่อนไขเป็นจริงจะเข้ามาทำงานในบล็อกของ if*/
    if (selectedYear !== null && selectedYear !== '' && selectedYear !== 'all') {  
        titleText += ' ปี ' + selectedYear;
/*เช็คเงื่อนไขว่า selectedMonth ไม่เป็น null, ไม่เป็นสตริงว่าง, และไม่มีค่าเท่ากับ 'all' โดยถ้าเงื่อนไขเป็นจริงจะเข้ามาทำงานในบล็อกของ if*/
        if (selectedMonth !== null && selectedMonth !== '' && selectedMonth !== 'all') {
            // Convert month code to month name
            //สร้าง array เก็บชื่อเดือนในภาษาไทยเพื่อใช้ในการแปลงรหัสเดือนเป็นชื่อเดือน
            var monthNames = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
            var monthIndex = parseInt(selectedMonth) - 1; //แปลงค่าของ selectedMonth เป็นตัวเลขและลบด้วย 1 เพื่อให้ได้ดัชนีของ array ชื่อเดือนที่ถูกต้อง
            titleText += ' เดือน ' + monthNames[monthIndex]; //เพิ่มข้อความ ' เดือน ' ตามด้วยชื่อเดือนที่ได้จากการแปลง selectedMonth เข้าไปในตัวแปร titleText
        }
    }

    // Build chart with initial title
    buildChart(titleText);

    // Function to update chart when year or month is selected
    function updateChart() {
        // Get selected year and month
        var selectedYear = document.getElementById('YearSelect').value;
        var selectedMonth = document.getElementById('MonthSelect').value;

        // Construct title text based on selected year and month
        var titleText = 'กราฟสรุปการใช้บริการทั้งหมด';
        if (selectedYear !== 'all') {
            titleText += ' ปี ' + selectedYear;
            if (selectedMonth !== 'all') {
                // Convert month code to month name
                var monthNames = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                titleText += ' เดือน ' + monthNames[selectedMonth - 1];
            }
        }

        // Update chart with new title
        buildChart(titleText);
    }
</script>
    <form action="" method="GET">
            <select id="YearSelect" name="year" required>
                <option value="">ปี(ค.ศ.)</option>
                <option value="all">ทุกปี</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
            </select>
        </div>
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
            <input type="submit" value="Filter">
        </div>
    </form>
</div>
</body>
</html>