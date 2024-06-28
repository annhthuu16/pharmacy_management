<?php
    //Change ini settings to make system more secure
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    //Session security
    session_set_cookie_params([
        'lifetime' => 1800,
        'domain' => 'localhost',
        'path' => '/',
        'secure' => true,   //https only
        'httponly' => true
    ]);

    //Start session cookie
    session_start();

    if (isset($_SESSION["user_id"])){
         //Update cookie ID every 30 minutes
        if(!isset($_SESSION['last_regeneration'])){
            regenerate_session_id_loggedin();
        }
        else{
            $interval = 60 * 30;    //time in seconds
            if(time() - $_SESSION['last_regeneration'] >= $interval) {
                regenerate_session_id_loggedin();
            }
        }
    } else {
        //Update cookie ID every 30 minutes
        if(!isset($_SESSION['last_regeneration'])){
            regenerate_session_id();
        }
        else{
            $interval = 60 * 30;    //time in seconds
            if(time() - $_SESSION['last_regeneration'] >= $interval) {
                regenerate_session_id();
            }
        }
    }

    function regenerate_session_id(){
        //Regenerate the session ID
        session_regenerate_id(true);

        //Update the last sesstion regeneration timestamp
        $_SESSION['last_regeneration'] = time();
    }
    function regenerate_session_id_loggedin(){
        //Regenerate the session ID
        session_regenerate_id(true);

        $userId = $_SESSION["user_id"];

        $newSessionId = session_create_id();
        $SessionId = $newSessionId."-".$userId;
        session_id($SessionId);

        //Update the last session regeneration timestamp
        $_SESSION['last_regeneration'] = time();
    }
