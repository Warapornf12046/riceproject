<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech City</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="img\TechTeam.png" rel="icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="header">
        <div class="menu">
            <div class="logo">
                <img onclick="document.location='index.html'" class="img-fluid h-100" src="img/TechTeam.png" alt="">
            </div>
            <a href="member.php">บริการและสินค้า</a>
            <a href="member1.php">สินค้า</a>
            <a href="point.php">คะแนนสะสม</a>
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>
    <section id="contact" class="contact">
        <div class="container">
            <div class="single-contact-box">
                <form method='post' action='promotion.php'>
                    <h2><center>รายชื่อสมาชิกที่ทำการแลกโปรโมชั่น</center></h2>
                    <script>
                        // Function to perform search using AJAX
                        function searchTable() {
                            var searchTerm = $('#searchInput').val();
                            $.ajax({
                                url: 'search.php', // ไฟล์ PHP ที่จะทำการค้นหาข้อมูลในฐานข้อมูล
                                method: 'POST',
                                data: { search: searchTerm },
                                success: function(response) {
                                    $('#membersTable').html(response);
                                }
                            });
                        }

                        // Call searchTable function when user types in the search input
                        $(document).ready(function() {
                            searchTable(); // เรียกใช้ฟังก์ชั่นเพื่อแสดงตารางสมาชิกเมื่อหน้าเว็บโหลดเสร็จ
                            $('#searchInput').on('input', function() {
                                searchTable(); // เรียกใช้ฟังก์ชั่นเพื่อค้นหาเมื่อผู้ใช้พิมพ์ในช่องค้นหา
                            });
                        });
                    </script>
                    <input class="search-box" type="text" id="searchInput" placeholder="ค้นหารายชื่อสมาชิก">
                    <div id="membersTable">
                        <!-- ตารางสมาชิกจะถูกแสดงที่นี่ -->
                    </div>
                    <div><br>
                        <center>
                            <input class="btn1" type="button" onclick="document.location='index.html'" value="ย้อนกลับ">
                            <input class="btn1" type="button" onclick="document.location='register.php'" value="สมัครสมาชิก">
                            <!-- Use JavaScript to submit the form asynchronously -->
                            <button class="btn" type="submit" name="selectname">ถัดไป</button>
                        </center>
                </form>
            </div>
        </div>
    </section>
    <p class="time" id="real-time"></p>
</body>
</html>
