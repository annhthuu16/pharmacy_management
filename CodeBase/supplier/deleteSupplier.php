<?php
session_start();
require_once('../include/databaseHandler.inc.php');
require('../include/function.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
    $paramResult = checkParamId('Id');
    if (is_numeric($paramResult)) {
        $indexValue = validate($paramResult);
        deleteFunction('suppliers', $indexValue);
        redirect('../supplier/viewSupplier.php', 'Supplier Deleted.');
    } else {
        redirect('../supplier/viewSupplier.php', 'Invalid parameter.');
    }
} else {
    header("Location: ../login/index.php");
}
