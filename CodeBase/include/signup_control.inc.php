<?php
    //Allowing type declaration
    declare(strict_types=1);

    function is_input_empty(string $Username, string $Pwd, string $FullName, string $Email, string $Role){
        if (empty($Username) || empty($Pwd) || empty($FullName) || empty($Email) || empty($Role))
        {
            return true;
        }
        else {
            return false;
        }
        
    }
    // function is_ID_taken(object $pdo, string $Id){
    //     if (get_ID($pdo, $Id))
    //     {
    //         return true;
    //     }
    //     else {
    //         return false;
    //     }
    // }

    function is_username_taken(object $pdo, string $Username){
        if (get_username($pdo, $Username))
        {
            return true;
        }
        else {
            return false;
        }
    }

    function is_email_invalid(string $Email){
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL))
        {
            return true;
        }
        else {
            return false;
        }
    }
    

    function is_email_registered(object $pdo, string $Email){
        if (get_email($pdo, $Email))
        {
            return true;
        }
        else {
            return false;
        }
    }
    function create_user(object $pdo, string $Username, string $FullName, string $Pwd, string $Email, string $Role){
        set_user($pdo, $Username, $FullName, $Pwd, $Email, $Role);
    }
    