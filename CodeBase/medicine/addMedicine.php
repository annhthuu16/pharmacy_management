<?php
require('../include/function.php');
include('../include/databaseHandler.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['saveMedicine'])) {
        $medicine_name = validate($_POST['medicine_name']);
        $price = validate($_POST['Price']);
        $type = validate($_POST['Type']);
        $id_supplier = validate($_POST['Id_supplier']);
        $quantity = validate($_POST['quantity']);
        $expiry_date = validate($_POST['expiry_date']);

        if ($medicine_name != '' && $price != '' && $type != '' && $id_supplier != '' && $quantity != '' && $expiry_date != '') {
            $medicine_data = [
                'medicine_name' => $medicine_name,
                'Price' => $price,
                'Type' => $type,
                'Id_supplier' => $id_supplier,
                'quantity' => $quantity,
                'expiry_date' => $expiry_date,
            ];

            $result = insert('medicines', $medicine_data);
            if ($result) {
                redirect('../medicine/addMedicine.php', 'Medicine added successfully');
            } else {
                redirect('../medicine/addMedicine.php', 'Something went wrong');
            }
        } else {
            redirect('../medicine/addMedicine.php', 'Please fill all required fields');
        }
    }

    if (isset($_POST['updateMedicine'])) {
        $currentMedicineId = validate($_POST['currentMedicineId']);
        $medicine_name = validate($_POST['medicine_name']);
        $price = validate($_POST['Price']);
        $type = validate($_POST['Type']);
        $id_supplier = validate($_POST['Id_supplier']);
        $quantity = validate($_POST['quantity']);
        $expiry_date = validate($_POST['expiry_date']);

        if ($medicine_name != '' && $price != '' && $type != '' && $id_supplier != '' && $quantity != '' && $expiry_date != '') {
            $medicine_updated_data = [
                'medicine_name' => $medicine_name,
                'Price' => $price,
                'Type' => $type,
                'Id_supplier' => $id_supplier,
                'quantity' => $quantity,
                'expiry_date' => $expiry_date,
            ];

            $result = update('medicines', $currentMedicineId, $medicine_updated_data);
            if ($result) {
                redirect('../medicine/viewMedicine.php', 'Medicine updated successfully');
            } else {
                redirect('../medicine/viewMedicine.php', 'Something went wrong');
            }
        } else {
            redirect("../medicine/viewMedicine.php?Id=<?=$currentMedicineId;?>", 'Please fill all required fields');
        }
    }
}

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
include('../medicine/header.html');
?>

<div class="main--content">
    <?php alertMessage(); ?>
    <form action="../medicine/addMedicine.php" method="POST">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Medicine</span>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search..." />
                </div>
                <img src="../image/img.png" alt="user-pic">
            </div>
        </div>

        <!-- Title -->
        <div class="supplier-items-container">
            <ul class="supplier-menu">
                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>Add Medicine</span>
                </li>
                <?php } ?>
                <li class="supplier-item">
                    <a href="../medicine/viewMedicine.php">
                        <span>View Medicine</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sp--wrapper">
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Name
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="medicine_name" placeholder="Enter medicine's name" onblur="validate(2)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Price
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Price" placeholder="Enter medicine's price" onblur="validate(4)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Type
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Type" placeholder="Enter medicine's type" onblur="validate(3)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Supplier ID
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Id_supplier" placeholder="Enter supplier ID" onblur="validate(3)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Quantity
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="quantity" placeholder="Enter quantity" onblur="validate(3)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Expiry Date
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="YYYY/MM/DD" onblur="validate(3)">
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6">
                    <button name="saveMedicine" type="submit" class="btn-block btn-primary">Add Medicine</button>
                </div>
            </div>
        </div>
    </form>
    <?php include('../include/footer.html'); ?>
</div>
</body>
</html>
<?php
    }else {
        header("Location: ../login/index.php");
    }
?>