<?php
    declare(strict_types=1);
    //Check if user existed in database
    function get_user(object $pdo, string $Username ){
        $query = "SELECT * FROM pharmacists WHERE Username = :Username;";

        //Prepared statement to separate query from data sent from user to prevent SQL injection
        $stmt = $pdo -> prepare($query);

        //Bind data to query
        $stmt -> bindParam(":Username", $Username);
        $stmt -> execute();

        //Return username if username already existed
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }
