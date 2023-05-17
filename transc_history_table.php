<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="webstyles.css">
		<title>RFID PROJECT</title>
        <style>
		body {
			overflow: auto;
		}
	</style>
	</head>
	
	<body>
		<h2>ESP32 + RFID + MySQL ACTED AS STUDENT LOAN MANAGEMENT</h2>
		<ul class="topnav">
			<li><a href="home.php">Home</a></li>
			<li><a href="topup.php">Top Up</a></li>
			<li><a class="active" href="transc_history.php">Transaction History</a></li>
			<li><a href="logout.php">Log Out</a></li>
            <img src="poli.png" alt="pkk">
		</ul>
        <br>
        <h3>Transaction History</h3>
    <div class="transc-table">
<?php
// start the session
session_start();

// get the table HTML from the session
$table_html = isset($_SESSION['table_html']) ? $_SESSION['table_html'] : '';

// display the table HTML if it's not empty
if (!empty($table_html)) {
    echo $table_html;
} else {
    echo "No transaction history to display.";
}
?>
</div>
</html>
