<?php

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get range from form data
    $range = $_POST['range'];

    // Redirect to chart page with range parameter
    header('Location: chart_dashboard.php?range=' . urlencode($range));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="webstyles.css">
    <title>Dashboard</title>
</head>
<body>
<h2>ESP32 + RFID + MySQL ACTED AS STUDENT LOAN MANAGEMENT</h2>
		<ul class="topnav">
			<li><a class="active" href="home.php">Home</a></li>
			<li><a href="topup.php">Top Up</a></li>
			<li><a href="transc_history.php">Transaction History</a></li>
			<li><a href="logout.php">Log Out</a></li>
            <img src="poli.png" alt="pkk">
		</ul>
		<br>
		<h3>My Dasboard</h3>
    <form method="post" action="dashboard.php">
    <label for="range" style="font-size: 20px;">Select a range:</label>
    <select name="range" id="range" onchange="enableButton()" style="width: 100%; margin-top: 17px; padding: 8px 16px; font-size: 16px; border: 3px solid #fbff00; border-radius: 4px; box-shadow: none;">
        <option value=""selected disabled>Select a range</option>
        <option value="day">Last 24 hours</option>
        <option value="week">Last 7 days</option>
        <option value="month">Last 30 days</option>
    </select>
    <br><br>
    <button type="submit" id="viewButton" disabled>>View</button>
    </form>
    <script>
    function enableButton() {
        document.getElementById("viewButton").disabled = false;
    }
</script>
</body>
</html>