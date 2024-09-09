$(document).ready(function() {
    showGraph();
});

function showGraph() {
    $.post("data_Pro.php", function(data) {
        console.log(data);
        var names = [];
        var quantities = [];

        for (var i in data) {
            names.push(data[i].ServiceProduct_Name);
            quantities.push(data[i].TotalOrders); // แก้จาก TotalPurchases เป็น TotalOrders
        }

        var chartdata = {
            labels: names,
            datasets: [{
                data: quantities,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        };

        var graphTarget = $("#graphCanvas");

        var pieChart = new Chart(graphTarget, {
            type: 'line',
            data: chartdata
        });
    });
}