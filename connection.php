<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_users";

if(!$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
