function updateRealTime() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var day = currentTime.getDay();
    var month = currentTime.getMonth();
    var year = currentTime.getFullYear();
    
    var thaiMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    var thaiMonth = thaiMonths[month];

    document.getElementById('real-time').innerHTML = "วัน" + getThaiDay(day) + " " + currentTime.getDate() + " " + thaiMonth + " " + (year + 543) + " | " + formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds) + " น.";
}

function formatTime(time) {
    return time < 10 ? "0" + time : time;
}

function getThaiDay(day) {
    var thaiDays = ["อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์"];
    return thaiDays[day];
}

setInterval(updateRealTime, 1000);
updateRealTime();


