<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($conn);
	

	// check if the form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// get the card number and voucher code from the form
		$uid = $_POST['Card_UID'];
		$voucher = $_POST['Voucher'];
		$password = $_POST['Password'];

		// validate the input
		if (empty($uid) || empty($voucher) || empty($password)) {
			$error = 'Card number, voucher code, and password are required.';
		} else {
			// connect to the database
			$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			
			// check if the connection is successful
			if ($conn->connect_error) {
				die('Connection failed: ' . $conn->connect_error);
			}
			
			// check if the voucher code exists
			$sql = "SELECT * FROM tb_topup WHERE Voucher = '$voucher'";
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$error = 'Invalid voucher code.';
			} else {
				// get the voucher details
				$row = $result->fetch_assoc();
				$amount = $row['Amount'];
				
				// check if the card UID and password match
				$sql = "SELECT * FROM tb_users_registered WHERE Card_UID = '$uid'";
				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$error = 'Invalid card UID or password.';
				} else {
					// verify the password
					$row = $result->fetch_assoc();
					$hashed_password = $row['Password'];
					if (password_verify($password, $hashed_password)) {
						// update the card balance
						$sql = "UPDATE tb_balance SET Balance = Balance + $amount WHERE Card_UID = '$uid'";
						if ($conn->query($sql) === TRUE) {
							// delete the voucher code from the database
							$sql = "DELETE FROM tb_topup WHERE Voucher = '$voucher'";
							$conn->query($sql);

							// record the transaction in the transaction history table
							$description = "Top up";
							date_default_timezone_set('Asia/Kuala_Lumpur');
							$date = date("Y-m-d");
							$time = date("H:i:s");
							$transaction_id = mt_rand(100000000, 999999999);
							$sql = "INSERT INTO tb_transaction (Transaction_ID,Card_UID, Amount, Description, Date, Time) VALUES ('$transaction_id','$uid', $amount, '$description','$date', '$time')";
							if ($conn->query($sql) === TRUE) {
								$message = 'Amount added successfully.';
							} else {
								$error = 'Error updating card balance: ' . $conn->error;
							}
						}
					} else {
						$error = 'Invalid card UID or password.';
					}
				}
				
				// close the database connection
				$conn->close();
			}
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="webstyles.css">
		<title>RFID PROJECT</title>
	</head>
	
	<body>
		<h2>ESP32 + RFID + MySQL ACTED AS STUDENT LOAN MANAGEMENT</h2>
		<ul class="topnav">
			<li><a href="home.php">Home</a></li>
			<li><a class="active" href="topup.php">Top Up</a></li>
			<li><a href="transc_history.php">Transaction History</a></li>
			<li><a href="logout.php">Log Out</a></li>
            <img src="poli.png" alt="pkk">
		</ul>
		<br>
		<h3>Top Up Page</h3>
        <form method="post">
				<table>
					<tr>
						<td colspan="3">TOP UP ACCOUNT</td>
					</tr>	
					<tr>
						<td>Card UID</td>
						<td>:</td>
						<td>
							<input type="text" name="Card_UID" placeholder="Card UID" id="id" required>
						</td>
					</tr>
					<tr>
						<td>Voucher Code</td>
						<td>:</td>
						<td>
							<input type="text" name="Voucher" placeholder="Voucher code" id="serialkey" required>
						</td>
					</tr>
					<tr>
						<td>Security Code</td>
						<td>:</td>
						<td>
							<input type="password" name="Password" placeholder="Password" id="password" required>
						</td>
					</tr>
                    </tr>
					    </td>
                            <div>
                                <button type="submit" name="button3" value="Top Up" class="btn btn-success">
                                    <i class="fas fa-coins"></i> <i class="fas fa-plus"></i>
                                    Top Up
                                </button>
                            </div>
                        </td>
					</tr>
	</body>
</html>

<!-- display the success/error message -->
<?php if (isset($message)): ?>
  <div style="color: green;"><?php echo $message; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
  <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>
