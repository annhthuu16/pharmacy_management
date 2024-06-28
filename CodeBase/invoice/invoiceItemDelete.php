<?php
    session_start();
    require_once('../include/databaseHandler.inc.php');
    require('../include/function.php');

    $paramResult = checkParamId('index');
    if (is_numeric($paramResult)) {
        $indexValue = validate($paramResult);

        if (isset($_SESSION['medicineItems']) && isset($_SESSION['medicineItems'][$indexValue])) {
            unset($_SESSION['medicineItems'][$indexValue]);
            unset($_SESSION['medicineItemIDs'][$indexValue]);

            // Reindex the session arrays
            $_SESSION['medicineItems'] = array_values($_SESSION['medicineItems']);
            $_SESSION['medicineItemIDs'] = array_values($_SESSION['medicineItemIDs']);

            redirect('invoiceForm.php', 'Item Removed');
        } else {
            redirect('invoiceForm.php', 'Item not found.');
        }
    } else {
        redirect('invoiceForm.php', 'Invalid parameter.');
    }

