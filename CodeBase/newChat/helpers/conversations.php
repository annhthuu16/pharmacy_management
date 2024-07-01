<?php 

function getConversation($Id, $pdo){
    /**
      Getting all the conversations 
      for current (logged in) user
    **/
    $sql = "SELECT * FROM conversations
            WHERE user_1=? OR user_2=?
            ORDER BY conversation_id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$Id, $Id]);

    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll();

        /**
          creating empty array to 
          store the user conversation
        **/
        $user_data = [];
        
        # looping through the conversations
        foreach($conversations as $conversation){
            # if conversations user_1 row equal to Id
            if ($conversation['user_1'] == $Id) {
            	$sql2  = "SELECT *
            	          FROM pharmacists WHERE Id=?";
            	$stmt2 = $pdo->prepare($sql2);
            	$stmt2->execute([$conversation['user_2']]);
            }else {
            	$sql2  = "SELECT *
            	          FROM pharmacists WHERE Id=?";
            	$stmt2 = $pdo->prepare($sql2);
            	$stmt2->execute([$conversation['user_1']]);
            }

            $allConversations = $stmt2->fetchAll();

            # pushing the data into the array 
            array_push($user_data, $allConversations[0]);
        }

        return $user_data;

    }else {
    	$conversations = [];
    	return $conversations;
    }  

}