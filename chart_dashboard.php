<?php
// Connect to the database using mysqli
include("connection.php");

if ($_GET['range'] == 'day') {
    $interval = 'INTERVAL 1 DAY';
} elseif ($_GET['range'] == 'week') {
    $interval = 'INTERVAL 1 WEEK';
} elseif ($_GET['range'] == 'month') {
    $interval = 'INTERVAL 1 MONTH';
} else {
    $interval = '';
}
// Retrieve the data from the database using a SELECT query
$sql = "SELECT Payment_Category, SUM(Amount) AS TotalAmount FROM tb_transaction WHERE Description IN ('Payment') AND Date >= DATE_SUB(NOW(), $interval) GROUP BY Payment_Category";
$result = mysqli_query($conn, $sql);

// Fetch the results into an array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="webstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="jquery.min.js"></script>
    <title>Payment Categories</title>
    <!-- Include the Google Charts API -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the column chart, passes in the data and
        // draws it.
        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Payment Category');
            data.addColumn('number', 'Total Amount');
            data.addRows([
                <?php
                // Loop through the data array and output each row as a JavaScript array
                foreach ($data as $row) {
                    echo "['" . $row['Payment_Category'] . "', " . $row['TotalAmount'] . "],";
                }
                ?>
            ]);

            // Set chart options
            var options = {
            title: 'Payment Categories',
            titleTextStyle: {
                color: '#fbff00',
                fontSize: 24,
                bold: true,
                textAlign: 'center', // center the title
            },
            width: 600,
            height: 400,
            hAxis: {
                title: 'Payment Category',
                titleTextStyle: {
                    color: '#fbff00',
                    fontSize: 18,
                    bold: true
                },
                textStyle: {
                    color: '#fbff00',
                    fontSize: 14
                }
            },
            vAxis: {
                title: 'Total Amount (RM)',
                titleTextStyle: {
                    color: '#fbff00',
                    fontSize: 18,
                    bold: true
                },
                textStyle: {
                    color: '#fbff00',
                    fontSize: 14
                },
                format: 'RM #,###.##', // format the axis labels as currency
                minValue: 0 // set the minimum value of the axis to 0
            },
            backgroundColor: '#34495E',
            legend: 'none',
            colors: ['#FFBF00'],
            tooltip: {
            textStyle: {
                fontSize: 12,
                color: '#000000'
            },
            prefix: 'RM ',
            suffix: '',
            isHtml: true,
            trigger: 'both'
        }
        };


        // Instantiate and draw the chart, passing in the data and options.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
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
		<h3>My Dashboard</h3>
    <style>
        #chart_div {
            width: 600px;
            height: 400px;
            margin: 0 auto;
            padding-top: 30px;
        }
    </style>
    <div id="chart_div"></div>
</body>
</html>

