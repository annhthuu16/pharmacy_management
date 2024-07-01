<?php  

function getUser($Username, $conn){
   $sql = "SELECT * FROM pharmacists 
           WHERE Username=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$Username]);

   if ($stmt->rowCount() === 1) {
   	 $user = $stmt->fetch();
   	 return $user;
   }else {
   	$user = [];
   	return $user;
   }
}