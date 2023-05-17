<?php
// include the database configuration file
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $uid = $_POST['Card_UID'];
    $amount = $_POST['Amount'];
    $payment_category = $_POST['Category'];

    // get the current balance
    $stmt = $conn->prepare("SELECT Balance FROM tb_balance WHERE Card_UID = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $balance = $row['Balance'];

        // check if the balance is sufficient
        if ($balance < $amount) {
            $error = 'Insufficient balance! Please Top Up';
        } else {
            // update the card balance
            $new_balance = $balance - $amount;
            $stmt = $conn->prepare("UPDATE tb_balance SET Balance = ? WHERE Card_UID = ?");
            $stmt->bind_param("ds", $new_balance, $uid);
            if ($stmt->execute()) {
                // record the transaction in the transaction history table
                $description = "Payment";
                date_default_timezone_set('Asia/Kuala_Lumpur');
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $transaction_id = mt_rand(100000000, 999999999);
                $stmt = $conn->prepare("INSERT INTO tb_transaction (Transaction_ID, Card_UID, Amount, Payment_Category, Description, Date, Time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isdssss", $transaction_id, $uid, $amount, $payment_category, $description, $date, $time);
                if ($stmt->execute()) {
                    $message = 'Payment successful';
                } else {
                    $error = 'Error recording transaction: ' . $stmt->error;
                }
            } else {
                $error = 'Error updating card balance: ' . $stmt->error;
            }
        }
    } else {
        $error = 'Card UID not found';
    }

    // close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="jquery.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="webstyles.css">
		<title>RFID PROJECT</title>
        <script>
        // Fetch the latest UIDresult value every 0.5 seconds
        setInterval(function() {
            $.get("UIDContainer.php", function(data) {
            $("#getUID").val(data);
            });
        }, 500); // 500 milliseconds = 0.5 seconds
        </script>
        </head>
	
	<body>
		<h2>ESP32 + RFID + MySQL ACTED AS STUDENT LOAN MANAGEMENT</h2>
		<ul class="topnav">
			<li><a class="active" href="pay.php">Pay</a></li>
            <img src="poli.png" alt="pkk">
		</ul>
		<br>
		<h3>Payment</h3>
        <form action="pay.php" method="post">
    <table>
        <tr>
            <td colspan="3">Confirm Your Payment</td>
        </tr>
        <?php
if (isset($error)) {
    echo '<p style="color:red; font-weight: 600; font-size: 25px;">' . $error . '</p>';
} elseif (isset($message)) {
    echo '<p style="color:green; font-weight: 600; font-size: 25px;">' . $message . '</p>';
}
?>
    
        <tr>
            <td>Card UID</td>
            <td>:</td>
            <td>
                <input type="text" placeholder="Card UID" name="Card_UID" id="getUID" value="<?php include 'UIDContainer.php'; ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>Category</td>
            <td>:</td>
            <td>
            <select name="Category" required style="width: 100%; padding: 8px 16px; font-size: 16px; border: 3px solid #fbff00; border-radius: 4px; box-shadow: none;">
                    <option value="">Select a category</option>
                    <option value="Food">Food</option>
                    <option value="Fees">Fees</option>
                    <option value="Book Reference">Book Reference</option>
                    <option value="Others">Others</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>:</td>
            <td>
                <input type="text" name="Amount" placeholder="Amount" id="Amount" required>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div>
                    <button type="submit" name="button3" value="Top Up" class="btn btn-success" onclick="confirmPayment(event)">
                        <i class="fas fa-money-check"></i>
                        Pay
                    </button>
                </div>
            </td>
        </tr>
        <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
    </table>
</form>
<script>
function confirmPayment(event) {
    event.preventDefault();
    if (document.getElementById("Amount").value.trim() === '') {
        swal('Please enter the amount.', '', 'warning');
        return false;
    }
    swal({
        title: 'Confirm Payment',
        text: 'Are you sure you want to proceed with the payment?',
        icon: 'warning',
        buttons: ['Cancel', 'Proceed'],
    }).then((proceed) => {
        if (proceed) {
            document.forms[0].submit();
            // hide the Card UID field
            document.getElementById("getUID").style.display = "none";
            // clear the stored card UID
            <?php
                $UIDresult = "";
                file_put_contents('UIDContainer.php', '<?php $UIDresult = ""; ?>');
            ?>
        } else {
            swal('Payment cancelled.', '', 'error');
        }
    });
}
</script>

	</body>
</html>



