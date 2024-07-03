<?php

// params to connect to the database

$dsn = "mysql:host=localhost;dbname=appotheke";
$dbusername = "root";
$dbpassword = "";

$conn = mysqli_connect("localhost", "root", "", "appotheke");

try{
	$pdo = new PDO($dsn, $dbusername, $dbpassword);
	$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
	echo "Connection failed: " . $e -> getMessage();
}