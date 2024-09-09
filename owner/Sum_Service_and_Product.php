<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>

<body>
    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>

    <?php
    // Connect to the database
    include('../include/config.php');
    // Set charset to utf8
    mysqli_set_charset($mysqli_p, "utf8");
    // Query to fetch data
    //คำสั่ง SQL ที่ใช้ในการดึงข้อมูลรายได้รวมจากการขายสินค้าและการให้บริการตามปีและเดือนที่ทำการ Group และ Order ตามเงื่อนไข
    $sql = "SELECT 
        YEAR(OrderService_Date) AS OrderYear, /*ปี (จาก OrderService_Date) เก็บเป็น OrderYear*/
        MONTH(OrderService_Date) AS OrderMonth, /*เดือน (จาก OrderService_Date) เก็บเป็น OrderMonth*/
        SUM(Total_Service + Total_Product) AS Combined_Total /*ผลรวม (Total_Service + Total_Product) เก็บเป็น Combined_Total*/
    FROM OrderService
    /*INNER JOIN ตาราง Orderproduct โดยใช้คอลัมน์ OrderService_Id เชื่อมโยงระหว่างสองตาราง*/
    INNER JOIN Orderproduct ON OrderService.OrderService_Id = Orderproduct.OrderProduct_Id
    GROUP BY OrderYear, OrderMonth  /*แยกตามปี*/
    ORDER BY OrderYear, OrderMonth;"; /*แยกตามเดือน*/

    $result = mysqli_query($mysqli_p, $sql);
    $data = array();

    // Loop through the result set and format data for Highcharts
    //นำข้อมูลที่ได้จากการ query database ด้วย mysqli_query() มาวนลูปทีละแถวด้วย mysqli_fetch_assoc() 
    //โดยที่ $result เป็นผลลัพธ์ของคำสั่ง SQL ที่ทำการ execute ไว้ก่อนหน้านี้
    while ($row = mysqli_fetch_assoc($result)) {
        $monthThai = array(  //เพื่อเก็บชื่อเดือนภาษาไทยในลำดับเดียวกับลำดับของเดือน โดยเริ่มต้นจากมกราคมถึงธันวาคมตามลำดับ
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        );
        //ดึงค่าของเดือนที่ได้จากฐานข้อมูลที่ชื่อว่า 'OrderMonth' และแปลงเป็นตัวเลขเต็ม (integer) 
        //โดยใช้ intval() และลบด้วย 1 เพื่อให้ได้ลำดับของเดือนในอาร์เรย์ $monthThai ที่เริ่มต้นที่ index 0
        $monthIndex = intval($row['OrderMonth']) - 1; // Convert month to 0-based index
        //นำค่าลำดับของเดือนที่ได้มาใช้เพื่อดึงชื่อของเดือนภาษาไทยจากอาร์เรย์ $monthThai เก็บในตัวแปร $monthName
        $monthName = $monthThai[$monthIndex];
        //นำข้อมูลที่ได้จากการ query มาเก็บในตัวแปร $data โดยใช้ปีของแต่ละข้อมูลเป็น key ของ $data 
        //และเก็บข้อมูลเดือนและยอดขายสินค้าในรูปแบบของอาร์เรย์ที่มี key 'month' และ 'totalProduct' 
        //โดยใช้ $monthName และ $row['Combined_Total'] ตามลำดับ
        $data[$row['OrderYear']][] = array(
            'month' => $monthName,
            'totalProduct' => floatval($row['Combined_Total'])
        );
    }
//แปลงข้อมูลที่อยู่ในตัวแปร $data ให้กลายเป็นรูปแบบ JSON ซึ่งเป็นรูปแบบการเก็บข้อมูลที่มีโครงสร้างแบบ Key-Value pairs 
//ซึ่งใช้สำหรับการสื่อสารข้อมูลระหว่างเว็บแอปพลิเคชันและเซิร์ฟเวอร์หรือการส่งข้อมูลระหว่างโปรแกรมต่าง ๆ 
    $data_json = json_encode($data);
    ?>

    <script type="text/javascript">
        //ใช้ JavaScript เพื่อดึงข้อมูลที่ถูกเก็บในรูปแบบ JSON จากตัวแปร $data_json ซึ่งเก็บข้อมูลเดือนและยอดขายสินค้าตามปี
        var data = <?php echo $data_json; ?>;
//สร้างกราฟโดยใช้ Highcharts โดยกำหนดตัวแปร 'container' เป็นตัวที่ใช้ในการแสดงผลของกราฟ
        Highcharts.chart('container', {
            chart: {  //กำหนดชนิดของกราฟเป็น line chart หรือกราฟเส้น
                type: 'line'
            },
            title: {
                text: 'รายได้รวมของสินค้าและบริการ'
            },
            xAxis: { //กำหนดแกน x ของกราฟเป็นเดือนตามลำดับ
                categories: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']
            },
            yAxis: { //กำหนดแกน y ของกราฟเป็น 'Sales (บาท)' เพื่อแสดงหัวข้อของแกน y
                title: {
                    text: 'Sales (บาท)'
                }
            },
            plotOptions: { // กำหนดตัวเลือกการพล็อตของกราฟเส้น เช่นการเปิดการใช้งาน Data Labels และการให้ Mouse Tracking สามารถทำได้
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            //กำหนดข้อมูลที่จะนำมาแสดงบนกราฟ โดยใช้ข้อมูลที่ถูกดึงมาจาก PHP ในลำดับนี้จะเป็นการวนลูปผ่านข้อมูลที่
            //ได้จาก $data และสร้าง series ของข้อมูลสำหรับแต่ละปี โดยเก็บชื่อปีและข้อมูลยอดขายสินค้าของแต่ละเดือนใน
            //รูปแบบ array ซึ่งจะนำไปใช้ในการแสดงบนกราฟแบบ line chart
            series: [ 
                <?php
                foreach ($data as $year => $monthsData) {
                    echo "{ name: '$year', data: [";
                    foreach ($monthsData as $monthData) {
                        echo $monthData['totalProduct'] . ",";
                    }
                    echo "] },";
                }
                ?>
            ]
        });
    </script>
</body>

</html>
