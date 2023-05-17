<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($conn);
// Retrieve balance from database based on current user's username
$username = $user_data['Username'];
$query = "SELECT Balance FROM tb_balance WHERE Username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
?>


<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="webstyles.css">
		<title>RFID PROJECT</title>
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
		<h3>Hello <?php echo $user_data['Username']; ?> , Welcome To Home Page !</h3><br>
		<h1>Your current balance is: RM <?php echo $row['Balance']; ?></h1>
		<button onclick="location.href='dashboard.php'" style="width: 300px; border-radius: 100px; font-size: 25px; font-weight: 800; margin-top: 50px;">View Dashboard</button>
	</body>
</html>

