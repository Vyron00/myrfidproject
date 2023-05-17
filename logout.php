<?php

session_start();

if(isset($_SESSION['serial_num']))
{
	unset($_SESSION['serial_num']);

}

header("Location: login.php");
die;