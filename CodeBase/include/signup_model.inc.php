<?php
    //Allowing type declaration
    declare(strict_types=1);
    // function get_ID (object $pdo, string $Id){
    //     $query = "SELECT Id FROM pharmacists WHERE Id = :Id;";

    //     //Prepared statement to separate query from data sent from user to prevent SQL injection
    //     $stmt = $pdo -> prepare($query);

    //     //Bind data to query
    //     $stmt -> bindParam(":Id", $Id);
    //     $stmt -> execute();

    //     //Return username if ID already existed
    //     $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    function get_username(object $pdo, string $Username){
        $query = "SELECT Username FROM pharmacists WHERE Username = :Username;";

        //Prepared statement to separate query from data sent from user to prevent SQL injection
        $stmt = $pdo -> prepare($query);

        //Bind data to query
        $stmt -> bindParam(":Username", $Username);
        $stmt -> execute();

        //Return username if username already existed
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function get_email(object $pdo, string $Email){
        $query = "SELECT Username FROM pharmacists WHERE Email = :Email;";

        //Prepared statement to separate query from data sent from user to prevent SQL injection
        $stmt = $pdo -> prepare($query);

        //Bind data to query
        $stmt -> bindParam(":Email", $Email);
        $stmt -> execute();

        //Return username if email already existed
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function set_user(object $pdo, $Username, string $FullName, string $Pwd, string $Email, string $Role){
        $query = "INSERT INTO pharmacists(Username, Pwd, FullName, Email, Role) 
        VALUES(:Username, :Pwd, :FullName, :Email, :Role);"; 

        //submit query to prepared statement 
        $stmt = $pdo->prepare($query);

        //hashing the password
        $options = [
            'cost' => 12
        ];
        $hashedPwd = password_hash($Pwd, PASSWORD_DEFAULT, $options);

        //bind user data 
        $stmt -> bindParam(":Username", $Username);
        $stmt -> bindParam(":Pwd", $hashedPwd);
        $stmt -> bindParam(":FullName", $FullName);
        $stmt -> bindParam(":Email", $Email);
        $stmt -> bindParam(":Role", $Role);
        
        $stmt->execute();

    }