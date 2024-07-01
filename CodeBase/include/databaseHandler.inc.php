<?php

// params to connect to the database

$dsn = "mysql:host=db;dbname=appotheke";
$dbusername = "appotheke";
$dbpassword = "vgupe2024_team3";

$conn = mysqli_connect("db", "appotheke", "vgupe2024_team3", "appotheke");

try{
	$pdo = new PDO($dsn, $dbusername, $dbpassword);
	$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
	echo "Connection failed: " . $e -> getMessage();
	exit();
}