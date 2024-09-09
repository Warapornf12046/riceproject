<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browser Market Share</title>

    <style type="text/css">
        /* กำหนดความกว้างขั้นต่ำและขั้นสูงสำหรับกราฟและตารางข้อมูล */
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px; /* ความกว้างขั้นต่ำ 310px */
            max-width: 1500px; /* ความกว้างขั้นสูง 1500px */
            margin: 1em auto; /* กำหนด margin 1em รอบด้านและจัดกลาง */
        }
/* กำหนดความสูงของกราฟ */
        #container {
            height: 400px;
        }
/* กำหนด สไตสำหรับตารางข้อมูล */
        .highcharts-data-table table {
            font-family: Verdana, sans-serif; /* กำหนด font family */
            border-collapse: collapse; /* ยุบเส้นขอบระหว่างเซลล์ */
            border: 1px solid #ebebeb; /* เส้นขอบ 1px สี #ebebeb */
            margin: 10px auto; /* กำหนด margin 10px รอบด้านและจัดกลาง */
            text-align: center; /* จัดข้อความตรงกลาง */
            max-width: 500px; /* ความกว้างขั้นสูง 500px */
        }
      /* กำหนดสไตล์สำหรับหัวตาราง (caption) */
        .highcharts-data-table caption {
            padding: 1em 0; /* padding 1em ด้านบนและ 0 ด้านล่าง */
            font-size: 1.2em; /* ขนาด font 1.2em */
            color: #555; /* สี #555 */
        }
     /* กำหนดสไตล์สำหรับหัวข้อคอลัมน์ (th) */
        .highcharts-data-table th {
            font-weight: 600; /* font weight 600 (ตัวหนา) */
            padding: 0.5em;  /* padding 0.5em */
        }
 /* กำหนดสไตล์สำหรับเซลล์ข้อมูล (td) และ หัวข้อคอลัมน์ (th) และ หัวตาราง (caption) */
        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }
/* กำหนดพื้นหลังของแถว */
        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;  /* พื้นหลังสี #f8f8f8 สำหรับแถว header และแถวคู่ */
        }
/* กำหนดพื้นหลังของแถวเวลา hover */
        .highcharts-data-table tr:hover {
            background: #f1f7ff;  /* พื้นหลังสี #f1f7ff เวลา hover เมาส์ */
        }
    </style>
</head>

<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>

    <figure class="highcharts-figure">
        <div id="container"></div> 
    </figure>

    <div class="Sol-1" style="position: absolute; top: 10px; right: 10px; display: flex; flex-direction: row;">
    <form action="" method="GET" style="display: flex; flex-direction: row;">
        <div class="Col-1" style="margin-right: 10px;">
                <select id="YearSelect" name="year" required>
                    <option value="">ปี(ค.ศ)</option>
                    <option value="all">ทุกปี</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div class="Col-2">
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

    <script type="text/javascript">
        //การเพิ่ม Event Listener ที่จะทำงานเมื่อเอกสาร HTML ของหน้าเว็บไซต์โหลดเสร็จสมบูรณ์แล้ว
        document.addEventListener("DOMContentLoaded", function() { 
            //เพิ่ม Event Listener ลงใน element ที่มี id เป็น 'filterForm' โดย Event Listener นี้จะทำงานเมื่อมีการ submit
            document.getElementById('filterForm').addEventListener('submit', function(e) { 
                e.preventDefault(); //เพื่อป้องกันไม่ให้ฟอร์ม submit แบบปกติ ซึ่งจะโหลดหน้าเว็บใหม่
                //รับค่าที่เลือกจากฟิลด์ที่มี id เป็น 'YearSelect' ตรงฟิลด์เลือก (dropdown) ของปี และเก็บค่านั้นไว้ในตัวแปร year.
                var year = document.getElementById('YearSelect').value;
                ////รับค่าที่เลือกจากฟิลด์ที่มี id เป็น 'MonthSelect' ตรงฟิลด์เลือก (dropdown) ของปี และเก็บค่านั้นไว้ในตัวแปร month.
                var month = document.getElementById('MonthSelect').value;
                //ร้าง URL ใหม่โดยนำ URL ปัจจุบันของหน้าเว็บมาต่อกันกับพารามิเตอร์ 'year' และ 'month' ที่มีค่าเป็นค่าที่เลือกจากฟอร์ม.
                var url = window.location.href.split('?')[0] + '?year=' + year + '&month=' + month;
                window.location.href = url; //เพื่ออัปเดต URL ของเว็บเพจไปยัง URL ใหม่ที่สร้างขึ้นในขั้นตอนก่อนหน้า
            });
        });
    </script>

    <?php
   // เชื่อมต่อฐานข้อมูล
    include('../include/config.php');
  // ตั้งค่า charset การเข้ารหัสตัวอักษรสำหรับการเชื่อมต่อฐานข้อมูล 
    mysqli_set_charset($mysqli_p, "utf8");
    //นับจำนวนสมาชิกในแต่ละเขตและเก็บผลลัพธ์ในชื่อ NumMembers
    $sql = "SELECT Member_Dustrict, COUNT(*) AS NumMembers FROM member";

    // ดึงปีและเดือนจาก URL parameters
    $year = isset($_GET['year']) ? $_GET['year'] : 'all';
    $month = isset($_GET['month']) ? $_GET['month'] : 'all';

    // ปรับ SQL query เพื่อกรองตามปีและ/หรือเดือนจาก URL parameters
    $sql .= " WHERE 1=1"; // Starting with a true condition
    if ($year != 'all') {
        $sql .= " AND YEAR(Member_Since) = '$year'";
    }
    if ($month != 'all') {
        $sql .= " AND MONTH(Member_Since) = '$month'";
    }
//สั่งให้ฐานข้อมูลจัดกลุ่มผลลัพธ์ตาม Member_Dustrict เพื่อนับจำนวนสมาชิกในแต่ละเขตได้อย่างถูกต้อง
    $sql .= " GROUP BY Member_Dustrict;";

    $result = mysqli_query($mysqli_p, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        //เพิ่ม array ใหม่เข้าไปใน $data
        $data[] = array('name' => $row['Member_Dustrict'], 'y' => intval($row['NumMembers']), 'drilldown' => $row['Member_Dustrict']);
    }
    //แปลง $data (array) เป็น JSON string
    $data2 = json_encode($data);
    ?>

    <script type="text/javascript">
        // Create the chart
        Highcharts.chart('container', {
              // กำหนดประเภทของกราฟเป็นแบบแท่ง
            chart: {
                type: 'column'
            },
              // กำหนดชื่อกราฟ
            title: {
                align: 'center',
                text: <?php
                // ตรวจสอบว่ามีการเลือกปีและเดือนหรือไม่
                    if ($year != 'all' && $month != 'all') {
                        echo "'กราฟแสดงจำนวนสมาชิกปี $year เดือน ";
                        // แปลงตัวเลขเดือนเป็นชื่อภาษาไทย
                        $thai_months = array(
                            1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน",
                            5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม",
                            9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"
                        );
                        echo $thai_months[intval($month)] . "'";
                    } else if ($year != 'all') {
                        // แสดงกราฟจำนวนสมาชิกปี $year
                        echo "'กราฟแสดงจำนวนสมาชิกปี $year'";
                    } else {
                         // แสดงกราฟจำนวนสมาชิกโดยไม่ระบุปีและเดือน
                        echo "'กราฟแสดงจำนวนสมาชิก'";
                    }
                    ?>
            },
            accessibility: { // เพิ่มการแจ้งเตือนผู้ใช้ที่เข้าถึงหน้าเว็บด้วย assistive technology
                announceNewData: {
                     // เปิดใช้งานการแจ้งเตือนเมื่อมีข้อมูลใหม่
                    enabled: true
                }
            },
            xAxis: { // กำหนดค่าแกน X
                type: 'category' // กำหนดประเภทของแกน X เป็นแบบหมวดหมู่
            },
            yAxis: {  // กำหนดค่าแกน Y
                title: {
                    text: 'จำนวนสมาชิก(คน)'  // กำหนดชื่อแกน Y
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: { // กำหนดค่า options
                series: {  // กำหนดความหนาของเส้นขอบข้อมูลเป็น 0
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true, // เปิดใช้งานป้ายข้อมูล
                        format: '{point.y:.0f}' // กำหนดรูปแบบของป้ายข้อมูล แสดงจำนวนเต็ม
                    }
                }
            },
            tooltip: {
                // กำหนดรูปแบบส่วนหัวของ tooltip
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                // กำหนดรูปแบบของเนื้อหา tooltip แสดงชื่อ, จำนวนสมาชิก และสี
                pointFormat: '<span style="color:{point.color}">{point.name} </span>: <b>จำนวน {point.y:.0f} คน</b><br/>'
            },
            series: [{ // กำหนดชุดข้อมูล
                name: 'ย้อนกลับ',
                colorByPoint: true, // กำหนดสีของข้อมูลแต่ละจุดตามค่าที่กำหนด
                data: <?php echo $data2; ?> // ดึงข้อมูลชุดที่ 2 มาแสดง
            }],

            drilldown: {
                series: [
                    <?php foreach ($data as $item): ?>
                   // สร้างชุดข้อมูลย่อย (drilldown series) สำหรับแต่ละรายการใน $data
                        {   name: '<?php echo $item['name']; ?>', // กำหนดชื่อชุดข้อมูลย่อย
                            id: '<?php echo $item['drilldown']; ?>', 
                            data: [
                                <?php
                                // สร้างคำสั่ง SQL เพื่อค้นหาจำนวนสมาชิกตามตำบลย่อย
                                $subdistrict_sql = "SELECT Member_Subdistrict, COUNT(*) AS NumMembers FROM member WHERE Member_Dustrict = '{$item['drilldown']}'";

                                // Add condition for month if selected
                                // เพิ่มเงื่อนไขสำหรับเดือนถ้ามีการเลือก
                                if ($month != 'all') {
                                    $subdistrict_sql .= " AND YEAR(Member_Since) = '$year' AND MONTH(Member_Since) = '$month'";
                                }
                               // เพิ่มการจัดกลุ่มตามตำบลย่อย
                                $subdistrict_sql .= " GROUP BY Member_Subdistrict;";
                                $subdistrict_result = mysqli_query($mysqli_p, $subdistrict_sql);

                                // Check if the query executed successfully
                                if (!$subdistrict_result) {
                                    die("Query failed: " . mysqli_error($mysqli_p));
                                }
                                //วนลูปประมวลผลข้อมูลที่ได้จากการรันคำสั่ง SQL
                                while ($subdistrict_row = mysqli_fetch_assoc($subdistrict_result)) {
                                    //แสดงข้อมูลแต่ละแถวในรูปแบบ array สำหรับสร้างกราฟ drilldown
                                    echo "['{$subdistrict_row['Member_Subdistrict']}', {$subdistrict_row['NumMembers']}],";
                                }
                                ?>
                            ]
                        },
                    <?php endforeach; ?>
                ]
            }
        });
    </script>
</body>

</html>
