<?php
session_start();

// Check whether there has been any new input in the last 5 seconds
$lastScanTime = time();
if (!isset($_SESSION['lastScanTime'])) {
    $_SESSION['lastScanTime'] = $lastScanTime;
} else {
    if ($lastScanTime - $_SESSION['lastScanTime'] > 5) {
        // no card has been scanned for 5 seconds, so clear the stored card UID
        $UIDresult = "";
        file_put_contents('UIDContainer.php', '<?php $UIDresult = ""; ?>');
    }
}
$_SESSION['lastScanTime'] = $lastScanTime;

// Check if the payment form has been submitted
if (isset($_POST['button3'])) {
    // Clear the stored card UID
    $UIDresult = "";
    file_put_contents('UIDContainer.php', '<?php $UIDresult = ""; ?>');
}

// Check if a card UID has been received
if (isset($_POST['UIDresult'])) {
    // Store the card UID in a PHP file for later retrieval
    $UIDresult=$_POST["UIDresult"];
    $Write="<?php $" . "UIDresult='" . $UIDresult . "'; " . "echo $" . "UIDresult;" . " ?>";
    file_put_contents('UIDContainer.php',$Write);
}
?>
