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
        <link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="webstyles.css">
		<title>RFID PROJECT</title>
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
        <style> 
            form {
                background: #222222;
                margin: 0 auto;
                padding: 10px;
                margin-top: 5%;
                text-align: center;
                max-width: 500px;
                box-shadow: 0px 5px 15px 8px rgb(0, 0, 0);
                border: 3px solid #fbff00;
                color:#fbff00;
            }
            
            label {
                display: block;
                margin-bottom: 10px;
                font-size: 16px;
                font-weight: bold;
            }
            
            input[type="text"],
            input[type="date"] {
                padding: 8px;
                width: 100%;
                border: 3px solid #fbff00;
                border-radius: 4px;
                box-sizing: border-box;
                margin-top: 6px;
                margin-bottom: 16px;
            }
            
            button[type="submit"] {
                background-color: #fbff00;
                color: #222222;
                border: 3px solid #000000;
                padding: 12px 20px;
                border-radius: 5px;
                cursor: pointer;
                margin-bottom: 9px;
            }
            
            button[type="submit"]:hover {
                background-color: #222222;
                color: #fbff00;
                border: 3px solid #fbff00;
            }
        </style>
		<h3>Transaction History</h3>
            <form action="transc_history.php" method="post">
                <label for="Card_UID">Card UID:</label>
                <input type="text" id="Card_UID" name="Card_UID" placeholder="Card UID" required>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>

                <button type="submit">View History</button>
            </form>
        </body>
    </html>


    <?php


// start the session
session_start();

// include the database configuration file
include('connection.php');

// get the input values from the form
if (isset($_POST['Card_UID'])) {
    $uid = $_POST['Card_UID'];
} else {
    $uid = "";
}
if (isset($_POST['start_date'])) {
    $start_date = $_POST['start_date'];
} else {
    $start_date = "";
}
if (isset($_POST['end_date'])) {
    $end_date = $_POST['end_date'];
} else {
    $end_date = "";
}

// connect to the database
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// check if the connection is successful
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// check if all input values are set
if (!empty($uid) && !empty($start_date) && !empty($end_date)) {

    // prepare the SQL query
    $sql = "SELECT * FROM tb_transaction WHERE Card_UID = '$uid' AND `date` BETWEEN '$start_date' AND '$end_date'";

    // execute the query and get the results
    $result = $conn->query($sql);

    // check if there are any results
    if ($result->num_rows > 0) {
        // display the transaction history in a table
        $table_html = "<div class='table-container'>";
        $table_html = "<table>";
        $table_html .= "<thead>";
        $table_html .= "<tr style>";
        $table_html .= "<th style='text-align:center; color: white;'>Transaction ID</th>";
        $table_html .= "<th style='text-align:center; color: white;'>Amount</th>";
        $table_html .= "<th style='text-align:center; color: white;'>Description</th>";
        $table_html .= "<th style='text-align:center; color: white;'>Date</th>";
        $table_html .= "<th style='text-align:center; color: white;'>Time</th>";
        $table_html .= "</tr>";
        $table_html .= "</thead>";
        $table_html .= "<tbody>";
        $table_html .= "</div>";


        while ($row = $result->fetch_assoc()) {
            $table_html .= "<tr>";
            $table_html .= "<td style='text-align:center; color: white;'>" . $row['Transaction_ID'] . "</td>";
            $table_html .= "<td style='text-align:center; color: white;'>RM " . $row['Amount'] . "</td>";
            $table_html .= "<td style='text-align:center; color: white;'>";
            if ($row['Description'] == 'Payment') {
                $table_html .= $row['Description'] . "<br>Category: " . $row['Payment_Category'];
            } else {
                $table_html .= $row['Description'];
            }
            $table_html .= "</td>";
            $table_html .= "<td style='text-align:center; color: white;'>" . $row['Date'] . "</td>";
            $table_html .= "<td style='text-align:center; color: white;'>" . $row['Time'] . "</td>";
            $table_html .= "</tr>";
        }

        $table_html .= "</tbody>";
        $table_html .= "</table>";

        // store the table HTML in a session variable
        $_SESSION['table_html'] = $table_html;

        // redirect to the next page
        header('Location: transc_history_table.php');
        exit;

    } else {
        // display a message if there are no results
        echo "<script>

    	swal({
         title: 'Unsuccessful!',
         text: 'No transactions found for this card and date range.',
         icon: 'error',
         timer: 3000,
         button: false,
         });
    	</script>";
      die;
    }
}
?>