<?php

declare(strict_types=1);

function check_login_error(){
    if (isset($_SESSION["errors_login"])){
        $errors = $_SESSION["errors_login"];

        echo "<br>";

        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }

    else if (isset($_GET["login"]) && $_GET["login"] == "success"){
        echo "<p class='success'>Login successful!</p>";
    }
}