<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "Clicked";
    // $Id = $_POST["Id"];
    $Username = $_POST["Username"];
    $Pwd = $_POST["Pwd"];
    $FullName = $_POST["FullName"];
    $Email = $_POST["Email"];  
    $Role = $_POST["Role"];
    try{
        //Connect to database
        require_once "databaseHandler.inc.php";
        require_once "signup_model.inc.php";
        require_once "signup_control.inc.php";

        //ERROR HANDLERS
        $errors = [];
        if (is_input_empty($Username, $Pwd, $FullName, $Email, $Role)){
            $errors["empty_input"] ="Fill in all field!";
        }
        // if (is_ID_taken($pdo, $Id)){
        //     $errors["ID_taken"] ="ID already taken!";
        // }
        if (is_username_taken($pdo, $Username)){
            $errors["username_taken"] ="Username already taken!";
        }
        if (is_email_invalid($Email)){
            $errors["invalid_email"] ="Invalid email used!";
        }
        if (is_email_registered($pdo, $Email)){
            $errors["email_used"] ="Email already registered!";
        }


        require_once 'config_session.inc.php';

            //Saving the form data if user made an error
        if ($errors){
            $_SESSION["errors_signup"] = $errors;

            $signup_data = [
                // "Id" => $Id,
                "Username" => $Username,
                "FullName" => $FullName,
                "Email" => $Email,
                "Role" => $Role
            ];

            $_SESSION["signup_data"] = $signup_data;

            //Send user back to signup page
            header("Location: ../signup/index.php");
            //stop running
            die();
        }

        create_user($pdo, $Username, $FullName, $Pwd, $Email, $Role);
        //redirect user back to front page after signing up into the website
        header("Location: ../login/index.php?signup=success");

        //close connection and statement
        $pdo = null;
        $stmt = null;

        die();

    } catch(PDOException $e){
        die("Query failed: " . $e -> getMessage());
    }
}
else {
    //Handle the case where the user didn't submit the form
    header("Location: ../signup/index.php");
    die();
}
