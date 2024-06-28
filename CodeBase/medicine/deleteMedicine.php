<?php
session_start();
require_once('../include/databaseHandler.inc.php');
require('../include/function.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
    $paramResult = checkParamId('Id');
    if (is_numeric($paramResult)) {
        $indexValue = validate($paramResult);
        deleteFunction('medicines', $indexValue);
        redirect('../medicine/viewMedicine.php', 'Medicine Deleted.');
    } else {
        redirect('../medicine/viewMedicine.php', 'Invalid parameter.');
    }
} else {
    header("Location: ../login/index.php");
}
