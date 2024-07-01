<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['user_username'])) {
	
	# database connection file
	include '../../include/databaseHandler.inc.php';

	# get the logged in user's username from SESSION
	$id = $_SESSION['user_id'];

	$sql = "UPDATE pharmacists
	        SET last_seen = NOW() 
	        WHERE Id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$id]);

}else {
	header("Location: ../../login/index.php");
	exit;
}