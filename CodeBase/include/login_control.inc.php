<?php
    declare(strict_types=1);
    // Check if username or password is empty
    function is_input_empty(string $Username, string $Pwd){
        if (empty($Username) || empty($Pwd)){
            return true;
        }
        else {
            return false;
        }
    }

    //Check if user existed in database
    function is_username_wrong(bool|array $result){
        if (!$result){
            return true;
        }
        else {
            return false;
        }

    }
    //Check if password is true
    function is_password_wrong(string $Pwd, string $hashedPwd){
        if (!password_verify($Pwd, $hashedPwd)){
            return true;
        }
        else {
            return false;
        }

    }