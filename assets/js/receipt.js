// JavaScript code to display the current date
var currentDate = new Date();
var options = { year: 'numeric', month: 'long', day: 'numeric' };
var formattedDate = currentDate.toLocaleDateString('th-TH', options);

// Set the formatted date to the 'current-date' element
document.getElementById('current-date').innerHTML = 'วันที่: ' + formattedDate;
// Function to handle the print functionality
function printReceipt() {
  window.print();
}