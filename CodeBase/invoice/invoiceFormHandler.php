<?php
// session_start();
$conn = mysqli_connect("localhost", "root", "", "appotheke");

require_once('../include/function.php');

if (!isset($_SESSION['medicineItems'])) {
    $_SESSION['medicineItems'] = [];
}
if (!isset($_SESSION['medicineItemIDs'])) {
    $_SESSION['medicineItemIDs'] = [];
}

if (isset($_POST['save_multiple_data'])) {
    $medicine_names = $_POST['medicine_name'];
    $quantities = $_POST['quantity'];

    foreach ($medicine_names as $index => $medicine_id) {
        $quantity = $quantities[$index];
        $medicine_id = mysqli_real_escape_string($conn, $medicine_id);
        $checkMedicine = mysqli_query($conn, "SELECT * FROM medicines WHERE Id = '$medicine_id' LIMIT 1");

        if ($checkMedicine && mysqli_num_rows($checkMedicine) > 0) {
            $row = mysqli_fetch_assoc($checkMedicine);

            if ($row['quantity'] < $quantity) {
                redirect('invoiceForm.php', 'Only ' . $row['quantity'] . ' quantity available for ' . $row['medicine_name'] . '!');
            }

            $medicineData = [
                'Id' => $row['Id'],
                'medicine_name' => $row['medicine_name'],
                'Price' => $row['Price'],
                'Type' => $row['Type'],
                'Id_supplier' => $row['Id_supplier'],
                'quantity' => $quantity,
                'expiry_date' => $row['expiry_date'],
            ];

            if (!in_array($row['Id'], $_SESSION['medicineItemIDs'])) {
                array_push($_SESSION['medicineItemIDs'],  $row['Id']);
                array_push($_SESSION['medicineItems'],  $medicineData);

                // $_SESSION['medicineItemIDs'][] = $row['Id'];
                // $_SESSION['medicineItems'][] = $medicineData;
            } else {
                foreach ($_SESSION['medicineItems'] as $key => $medicineSessionItem) {
                    if ($medicineSessionItem['Id'] == $row['Id']) {
                        // $_SESSION['medicineItems'][$key]['quantity'] += $quantity;
                        $temporary_quantity = $_SESSION['medicineItems'][$key]['quantity'] + $quantity;
                        if ($temporary_quantity > $row['quantity']) {
                            redirect('invoiceForm.php', 'Only ' . $row['quantity'] . ' quantity available for ' . $row['medicine_name'] . '!');
                        }
                    }
                }
            }
        } else {
            redirect('invoiceForm.php', 'Medicine not found.');
        }
    }
    redirect('invoiceForm.php', 'Items Added.');
}

if (isset($_POST['updateQuantity'])) {
    $index = $_POST['index'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['medicineItems'][$index])) {
        $medicineId = $_SESSION['medicineItems'][$index]['Id'];
        
        // Check if the new quantity does not exceed the available quantity
        $checkMedicine = mysqli_query($conn, "SELECT * FROM medicines WHERE Id = '$medicineId' LIMIT 1");
        if ($checkMedicine && mysqli_num_rows($checkMedicine) > 0) {
            $row = mysqli_fetch_assoc($checkMedicine);
            if ($quantity > $row['quantity']) {
                echo json_encode(['status' => 'error', 'message' => 'Only ' . $row['quantity'] . ' quantity available for ' . $row['medicine_name'] . '!']);
                exit;
            }

            $_SESSION['medicineItems'][$index]['quantity'] = $quantity;
            echo json_encode(['status' => 'success', 'message' => 'Quantity updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Medicine not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found in session.']);
    }
    exit;
}

if (isset($_POST['proceedToPlaceBtn'])){
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    //Checking if customer already exist (by phone number)
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone_number='$phone' LIMIT 1");
    if($checkCustomer){
        if (mysqli_num_rows($checkCustomer) > 0){
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;

            jsonResponse(200, 'success', 'Customer Found.');
        } else {
            $_SESSION['cphone'] = $phone;
            jsonResponse(404, 'warning', 'Customer Not Found.');
        }
    } else {
        jsonResponse(500, 'error', 'Somthing went wrong. Please try again later.');
    }

}

if (isset($_POST['saveCustomerBtn'])){
    $c_name = validate($_POST['c_name']);
    $c_phone = validate($_POST['c_phone']);

    $data = [
        'Name' => $c_name,
        'phone_number' => $c_phone,
    ];
    $result = insert('customers', $data);
    if ($result) {
        jsonResponse(200, 'success', 'Customer Created Successfully.');
    } else {
        jsonResponse(500, 'error', 'Somthing went wrong. Please try again later.');
    }

}

if (isset($_POST['saveInvoiceBtn'])){
    $phone =validate($_SESSION['cphone']);
    $payment_mode = validate($_SESSION['payment_mode']);

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone_number='$phone' LIMIT 1");
    if(!$checkCustomer){
        jsonResponse(500, 'error', 'Somthing went wrong. Please try again later.');
    }
    if(mysqli_num_rows($checkCustomer) > 0){
        $customerData = mysqli_fetch_assoc($checkCustomer);

        if(!isset($_SESSION['medicineItems']) || count($_SESSION['medicineItems']) == 0){
            jsonResponse(404, 'warning', 'No Items Found.');
        }

        $total = 0;
        $sessionMedicines = $_SESSION['medicineItems'];
        foreach ($sessionMedicines as $medicine){
            $total += $medicine['Price'] * $medicine['quantity'];
        }

        //Get current pharmacist info
        $checkPharmacist = mysqli_query($conn, "SELECT * FROM pharmacists WHERE username = '".$_SESSION['user_username']."' LIMIT 1");
        if($checkPharmacist){
            if (mysqli_num_rows($checkPharmacist) > 0){
                $pharmacistData = mysqli_fetch_assoc($checkPharmacist);
                $_SESSION['user_username'] = $pharmacistData['Username'];
                $_SESSION['Pharmacist_Id'] = $pharmacistData['Id'];
                jsonResponse(200, 'success', 'Pharmacist Found.');
            } else {
                jsonResponse(404, 'error', 'Pharmacist Not Found.');
            }
        } else {
            jsonResponse(500, 'error', 'Somthing went wrong. Please try again later.');
        }

        //Create invoice table
        $data = [
            'Pharmacist_Id' => $_SESSION['Pharmacist_Id'],
            'Customer_Id' => $customerData['Id'],
            'Total' => $total,
            'payment_mode' => $payment_mode,
            'Date' => date('Y-m-d'),

        ];
        $result = insert('sales', $data);

        //Store invoice medicine items in database
        $lastInvoiceId = mysqli_insert_id($conn);
        foreach($sessionMedicines as $medicine){
            $medicineId = $medicine['Id'];
            $price = $medicine['Price'];
            $quantity = $medicine['quantity'];

            //Inserting invoice items
            $dataInvoiceMedicine = [
                'Invoice_Id' => $lastInvoiceId,
                'Medicine_Id' => $medicineId,
                'Price' => $price,
                'quantity' => $quantity,
            ];
            $invoiceMedicineQuery = insert('invoice_medicines', $dataInvoiceMedicine);

            //Checking for medicine quantity and then decreasing quantity in stock
            $checkMedicineQtyQuery = mysqli_query($conn, "SELECT * FROM medicines WHERE Id = '$medicineId' LIMIT 1");
            $medicineQtyData = mysqli_fetch_assoc($checkMedicineQtyQuery);
            $newQty = $medicineQtyData['quantity'] - $quantity;

            $dataUpdate =[
                'quantity' => $newQty,
            ];

            $updateMedicineQtyQuery = update('medicines', $medicineId, $dataUpdate);
        }

        //Empty all session data
        unset($_SESSION['medicineItems']);
        unset($_SESSION['medicineItemIDs']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);

        jsonResponse(200, 'success', 'Invoice Created Successfully.');

    } else {
        jsonResponse(400, 'warning', 'Customer Not Found.');
    }
}