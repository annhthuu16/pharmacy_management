<?php
    // include/logout.inc.php
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../login/index.php");
    exit();
?>