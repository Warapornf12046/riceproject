<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tech City</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img\TechTeam.png" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300&display=swap" rel="stylesheet">


    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <a href="chart.php"
            class="btn btn-primary btn-floating btn-lg rounded-circle position-fixed bottom-0 end-0 m-4">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Dashboard</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img\TechTeam.png" alt="" style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Owner</h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i
                            class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                    </div>

                    <a href="chart.php" class="nav-item nav-link"><i class="fa fa-chart-pie text-primary"></i>Charts</a>
                </div>
        </div>
        </nav>
    </div>
    <!-- Sidebar End -->


    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
            </a>
            <a href="#" class="sidebar-toggler flex-shrink-0">
                <i class="fa fa-bars"></i>
            </a>
            <div class="navbar-nav align-items-center ms-auto">


                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img class="rounded-circle me-lg-2" src="img\TechTeam.png" alt=""
                            style="width: 40px; height: 40px;">
                        <span class="d-none d-lg-inline-flex">Owner</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="../login.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Navbar End -->


        <!-- Sale & Revenue Start -->
        <?php
    include('../include/config.php');
    // ตั้งค่า charset การเข้ารหัสตัวอักษรสำหรับการเชื่อมต่อฐานข้อมูล 
    mysqli_set_charset($mysqli_p, "utf8");

    // Initialize total service and product variables for 2024
    $totalService2024 = 0;
    $totalProduct2024 = 0;

    // Query to fetch total service and product for the year 2024
    $sql = "SELECT 
    SUM(OrderService.Total_Service) AS TotalService2024,
    SUM(OrderProduct.Total_Product) AS TotalProduct2024
    FROM OrderService
    INNER JOIN Orderproduct ON OrderService.OrderService_Id = OrderProduct.OrderProduct_Id
    WHERE YEAR(OrderService.OrderService_Date) = 2024 AND YEAR(Orderproduct.OrderProduct_Date) = 2024";

    $result = mysqli_query($mysqli_p, $sql);

    if ($result->num_rows > 0) {
    // Fetch the result row
    $row = $result->fetch_assoc();

    // Access the total service and product for the year 2024
    $totalService2024 = $row['TotalService2024'];
    $totalProduct2024 = $row['TotalProduct2024'];
    }

    // Close the database connection
    $mysqli_p->close();

    ?>
        <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <div class="container">
                                    <p class="mb-2">ยอดรวมค่าบริการและสินค้าของปี 2024  </p>
                                    <h6 class="mb-0"><?php echo number_format($totalService2024 + $totalProduct2024, 2); ?> บาท</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <?php
                                include('../include/config.php');
                                // คำสั่ง SQL เพื่อนับจำนวนสมาชิกทั้งหมดในตาราง Member
                                $sql = "SELECT COUNT(*) AS TotalMembers FROM Member";
                                $result = $mysqli_p->query($sql);

                                $totalMembers = 0; // กำหนดค่าเริ่มต้นของจำนวนสมาชิกทั้งหมด

                                // เช็คว่า query สำเร็จหรือไม่
                                if ($result) {
                                    // ดึงข้อมูลจำนวนสมาชิกจากฐานข้อมูลและเก็บไว้ในตัวแปร $totalMembers
                                    $row = $result->fetch_assoc();
                                    $totalMembers = $row['TotalMembers'];
                                }
                                // ปิดการเชื่อมต่อกับฐานข้อมูล
                                $mysqli_p->close();
                                ?>
                                <p class="mb-2">สมาชิกทั้งหมด </p>
                                <h6 class="mb-0"><?php echo $totalMembers; ?> คน </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <div class="container">
                                    <p class="mb-2">ยอดรวมค่าบริการของปี 2024  </p>
                                    <h6 class="mb-0"><?php echo number_format($totalService2024, 2); ?> บาท</h6>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <div class="container">
                                    <p class="mb-2">ยอดรวมค่าสินค้าของปี 2024  </p>
                                    <h6 class="mb-0"><?php echo number_format($totalProduct2024, 2); ?> บาท</h6>
                                </div>
                                
                            </div>
                        </div>
                    </div>
               
        <!-- Sale & Revenue End -->

        <!-- Sales Chart Start -->
        <head>
    <title>Highcharts Example</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>

<body>
    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>
    <body>
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

    <?php
    // Include SumProSer.php file
    include('Sum_Service_and_Product.php');
    ?>

    <script type="text/javascript">
        var data = <?php echo $data_json; ?>;

        Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'รายได้รวมของสินค้าและบริการ'
            },
            xAxis: {
                categories: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']
            },
            yAxis: {
                title: {
                    text: 'Sales (บาท)'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
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
<body>
    <iframe src="Total_Service_and_Product.php" width="100%" height="600px" frameborder="0"></iframe>
    <iframe src="Member.php" width="100%" height="700px" frameborder="0"></iframe>
</body>

        <!-- Sales Chart End -->


        <!-- Recent Sales Start -->
        <div class="container-fluid pt-4 px-2">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    
                </div>
            </div>
        </div>
        <!-- Recent Sales End -->

        <!-- Back to Top -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            $('#customer-filter').on('input', function () {
                var value = $(this).val().toLowerCase();
                $('tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>