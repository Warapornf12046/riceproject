(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });


    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });


    // Progress Bar
    $('.pg-bar').waypoint(function () {
        $('.progress .progress-bar').each(function () {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, { offset: '80%' });


    // Calender
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: true,
        loop: true,
        nav: false
    });


   // Single Line Chart
   var lineChartCanvas = $("#line-chart");
   if (lineChartCanvas.length) {
       fetch('http://localhost:3000/api/receipt-total', {
           mode: 'cors'
       })
       .then(response => response.json())
       .then(data => {
           var ctx3 = lineChartCanvas.get(0).getContext("2d");
           var myChart3 = new Chart(ctx3, {
               type: "line",
               data: {
                   labels: Array.from({ length: data.length }, (_, i) => i + 1), // สร้างลำดับเลขของข้อมูล
                   datasets: [{
                       label: "Receipt Total",
                       fill: false,
                       backgroundColor: "rgba(0, 156, 255, .3)",
                       data:  data// ใช้ข้อมูลที่ดึงมาจาก API
                   }]
               },
               options: {
                   responsive: true
               }
           });
       })
       .catch(error => {
           console.error('Failed to fetch data from API: ' + error);
       });
   }



    // Pie Chart
    var pieChartCanvas1 = $("#pie-chart1");
    if (pieChartCanvas1.length) {
        fetch('http://localhost:3000/api/service', {
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            var ctx1 = pieChartCanvas1.get(0).getContext("2d");
            var myChart1 = new Chart(ctx1, {
                type: "pie",
                data: {
                    labels: data.map(item => item.ServiceProduct_Name),
                    datasets: [{
                        data: data.map(item => item.Usage_Count),
                        backgroundColor: [
                            "rgba(0, 156, 255, .7)", // สีฟ้าเข้ม
                            "rgba(255, 99, 132, .7)", // สีแดงเข้ม
                            "rgba(54, 162, 235, .7)", // สีน้ำเงินเข้ม
                            "rgba(255, 205, 86, .7)", // สีเหลืองเข้ม
                            "rgba(75, 192, 192, .7)", // สีเขียวเข้ม
                            "rgba(255, 159, 64, .7)", // สีส้มเข้ม
                            "rgba(153, 102, 255, .7)", // สีม่วงเข้ม
                            "rgba(255, 0, 255, .7)", // สีม่วงสดใส
                            // เพิ่มสีเพิ่มเติมตามจำนวนข้อมูลที่มี
                        ]
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    aspectRatio: 1
                }
            });
            // เพิ่ม CSS เพื่อจัดให้อยู่ตรงกลาง
            pieChartCanvas1.css({
                "margin": "0 auto",
                "display": "block"
            });
        })
        .catch(error => {
            console.error('Failed to fetch data from API: ' + error);
        });
    }

        var pieChartCanvas2 = $("#pie-chart2"); // เลือก Canvas สำหรับ Pie Chart 2
        if (pieChartCanvas2.length) {
        fetch('http://localhost:3000/api/product')
            .then(response => response.json())
            .then(data => {
                var ctx2 = pieChartCanvas2.get(0).getContext("2d");
                var myChart2 = new Chart(ctx2, {
                    type: "pie",
                    data: {
                        labels: ["Bran", "Husk", "Chunks", "Broken"],
                        datasets: [{
                            data: [data[0].countbran, data[0].counthusk, data[0].countchunks, data[0].countbroken],
                            backgroundColor: [
                                "rgba(255, 99, 132, .7)",
                                "rgba(255, 159, 64, .7)",
                                "rgba(255, 205, 86, .7)",
                                "rgba(75, 192, 192, .7)"
                            ]
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        aspectRatio: 1
                    }
                });
                // เพิ่ม CSS เพื่อจัดให้อยู่ตรงกลาง
                pieChartCanvas2.css({
                    "margin": "0 auto",
                    "display": "block"
                });
            })
            .catch(error => {
                console.error('Failed to fetch data from API: ' + error);
            });
    }





    // Single Bar Chart1
    var ctx4 = $("#bar-chart").get(0).getContext("2d");
    var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["รำ", "แกลบ", "ข้าวท่อน", "ข้าวปลาย",],
            datasets: [
                {
                    label: "จำนวนที่ซื้อ(กก.)",
                    backgroundColor: [
                        "rgba(0, 156, 255, .7)",
                        "rgba(0, 156, 255, .6)",
                        "rgba(0, 156, 255, .5)",
                        "rgba(0, 156, 255, .4)",
                        "rgba(0, 156, 255, .3)"
                    ],
                    data: [55, 49, 44, 24, 15]
                },
                {
                    label: "จำนวนลูกค้าที่มา",
                    backgroundColor: [
                        "rgba(255, 99, 132, .7)",
                        "rgba(255, 99, 132, .6)",
                        "rgba(255, 99, 132, .5)",
                        "rgba(255, 99, 132, .4)",
                        "rgba(255, 99, 132, .3)"
                    ],
                    data: [30, 42, 35, 18, 25]
                }
            ]
        },
        options: {
            responsive: true
        }
    });
    // Single Bar Chart2
    var ctx4 = $("#bar-chart2").get(0).getContext("2d");
    var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["สีข้าว", "คัด/ฝัดเมล็ดข้าว", "อบข้าว",],
            datasets: [
                {
                    label: "จำนวนข้าว(ถัง)",
                    backgroundColor: [
                        "rgba(0, 156, 255, .7)",
                        "rgba(0, 156, 255, .6)",
                        "rgba(0, 156, 255, .5)",
                        "rgba(0, 156, 255, .4)",
                        "rgba(0, 156, 255, .3)"
                    ],
                    data: [55, 49, 44, 24, 15]
                },
                {
                    label: "จำนวนครั้งที่มา",
                    backgroundColor: [
                        "rgba(255, 99, 132, .7)",
                        "rgba(255, 99, 132, .6)",
                        "rgba(255, 99, 132, .5)",
                        "rgba(255, 99, 132, .4)",
                        "rgba(255, 99, 132, .3)"
                    ],
                    data: [30, 42, 35, 18, 25]
                }
            ]
        },
        options: {
            responsive: true
        }
    });
    // Multiple Bar Chart
    var ctx5 = $("#worldwide-sales").get(0).getContext("2d");
    var myChart5 = new Chart(ctx5, {
        type: "bar",
        data: {
            labels: ["ซื้อสินค้า", "ใช้บริการ",],
            datasets: [{
                data: [15, 30, 55, 65, 60, 80, 95],
                backgroundColor: "rgba(0, 156, 255, .7)"
            },
           
            ]
        },
        options: {
            responsive: true
        }
    });

    
    

    

})(jQuery);

