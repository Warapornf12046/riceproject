<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>กราฟสรุปการซื้อสินค้าทั้งหมด</title>
    

    <style type="text/css">
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

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
// Query to fetch data
//นับจำนวน records แยกตามประเภทของข้าวเปลือก (bran, husk, chunks, broken)เก็บผลลัพธ์ใน alias ดังนี้
$sql = "SELECT 
        SUM(CASE WHEN Kg_Rice_bran != 0 THEN 1 ELSE 0 END) AS countbran, /*countbran: จำนวน records ที่มี Kg_Rice_bran มากกว่า 0*/
        SUM(CASE WHEN Kg_Husk != 0 THEN 1 ELSE 0 END) AS counthusk, /*counthusk: จำนวน records ที่มี Kg_Husk มากกว่า 0*/
        SUM(CASE WHEN Kg_Rice_chunks != 0 THEN 1 ELSE 0 END) AS countchunks, /*countchunks: จำนวน records ที่มี Kg_Rice_chunks มากกว่า 0*/
        SUM(CASE WHEN Kg_Broken_rice != 0 THEN 1 ELSE 0 END) AS countbroken /*ountbroken: จำนวน records ที่มี Kg_Broken_rice มากกว่า 0*/
        FROM 
        orderproduct ";


// Check if year and month are selected
if(isset($_GET['year']) && isset($_GET['month'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];

    // If both year and month are selected
    if($year != 'all' && $month != 'all') {
        // Add conditions to SQL query to filter by year and month
        $sql .= " WHERE YEAR(OrderProduct_Date) = '$year' AND MONTH(OrderProduct_Date) = '$month'";
    }
    // If only year is selected
    else if($year != 'all') {
        // Add condition to SQL query to filter by year only
        $sql .= " WHERE YEAR(OrderProduct_Date) = '$year'";
    }
}

$result = mysqli_query($mysqli_p, $sql);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {  //ดึงข้อมูลจากผลลัพธ์ที่ได้จากการ query และนำมาใส่ในตัวแปร $row ในแต่ละรอบของลูป while
// เพิ่มข้อมูลลงในตัวแปร $data ในรูปแบบของ array โดยกำหนด key 'name' ให้เป็นชื่อ "รำข้าว,แกลบ,ข้าวท่อน,ข้าวปลาย" และ key 'y' 
//ให้เป็นค่าจำนวนรายการที่มีค่าในคอลัมน์ countbran โดยใช้ intval() เพื่อแปลงค่าเป็นตัวเลขจำนวนเต็ม
    $data[] = array("name" => "รำข้าว", "y" => intval($row['countbran']));
    $data[] = array("name" => "แกลบ", "y" => intval($row['counthusk']));
    $data[] = array("name" => "ข้าวท่อน", "y" => intval($row['countchunks']));
    $data[] = array("name" => "ข้าวปลาย", "y" => intval($row['countbroken']));
}
$data2 = json_encode($data); //แปลงข้อมูลในตัวแปร $data ให้อยู่ในรูปแบบ JSON และเก็บผลลัพธ์ในตัวแปร $data2
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
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' // ตั้งค่ารูปแบบของข้อมูลที่แสดงเมื่อ hover บนจุดข้อมูล
            },
            accessibility: {
                point: {  
                    valueSuffix: '%' //ตั้งค่า suffix ของค่าข้อมูลเป็น "%"
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer', //ตั้งค่า cursor เป็น "pointer" เมื่ออยู่เหนือแผนภูมิ
                    dataLabels: {
                        enabled: true, // เปิดใช้งานการแสดงข้อมูลบนแผนภูมิ
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%' // รูปแบบการแสดงข้อมูลที่ต้องการเป็นเปอร์เซ็นต์
                    },
                    showInLegend: true //แสดงรายการข้อมูลบนแผนภูมิ
                }
            },
            series: [{
                name: 'จำนวนสินค้า',
                colorByPoint: true,
                data: <?php echo $data2; ?> //แทรกข้อมูล ($data2) ลงในตัวเลือก data ของแผนภูมิ
            }]
        });
    }

    // Get selected year and month from URL parameters
    var urlParams = new URLSearchParams(window.location.search);
    var selectedYear = urlParams.get('year');
    var selectedMonth = urlParams.get('month');

    // Set initial title based on selected year and month
    var titleText = 'กราฟสรุปการซื้อสินค้าทั้งหมด';
    if (selectedYear !== null && selectedYear !== '' && selectedYear !== 'all') {
        titleText += ' ปี ' + selectedYear;
        if (selectedMonth !== null && selectedMonth !== '' && selectedMonth !== 'all') {
            // Convert month code to month name
            var monthNames = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
            var monthIndex = parseInt(selectedMonth) - 1;
            titleText += ' เดือน ' + monthNames[monthIndex];
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
        var titleText = 'กราฟสรุปการซื้อสินค้าทั้งหมด';
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
