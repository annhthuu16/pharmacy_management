<?php
require('../include/function.php');
include('../include/databaseHandler.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['saveSupplier'])) {
        $supplier_name = validate($_POST['supplier_name']);
        $supplier_phone_number = validate($_POST['supplier_phone_number']);
        $supplier_email = validate($_POST['supplier_email']);

        if ($supplier_name != '' && $supplier_email != '' && $supplier_phone_number != '') {
            $phoneNumberCheck = mysqli_query($conn, "SELECT * FROM suppliers WHERE phone_number='$supplier_phone_number'");
            $emailCheck = mysqli_query($conn, "SELECT * FROM suppliers WHERE email='$supplier_email'");
            
            if ($phoneNumberCheck && $emailCheck) {
                if (mysqli_num_rows($phoneNumberCheck) > 0) {
                    redirect('../supplier/addSupplier.php', 'Phone already used by another supplier account');
                }
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('../supplier/addSupplier.php', 'Email already used by another supplier account');
                }
            }

            $supplier_data = [
                'name' => $supplier_name,
                'phone_number' => $supplier_phone_number,
                'email' => $supplier_email,
            ];

            $result = insert('suppliers', $supplier_data);
            if ($result) {
                redirect('../supplier/addSupplier.php', 'Supplier created successfully');
            } else {
                redirect('../supplier/addSupplier.php', 'Something went wrong');
            }
        } else {
            redirect('../supplier/addSupplier.php', 'Please fill all required fields');
        }
    }

    if (isset($_POST['updateSupplier'])) {
        $currentSupplierId = validate($_POST['currentSupplierId']);
        $currentSupplierName = validate($_POST['currentSupplierName']);
        $currentSupplierPhoneNumber = validate($_POST['currentSupplierPhoneNumber']);
        $currentSupplierEmail = validate($_POST['currentSupplierEmail']);

        if ($currentSupplierName != '' && $currentSupplierPhoneNumber != '' && $currentSupplierEmail != '') {
            $phoneNumberCheck = mysqli_query($conn, "SELECT * FROM suppliers WHERE phone_number='$currentSupplierPhoneNumber'");
            $emailCheck = mysqli_query($conn, "SELECT * FROM suppliers WHERE email='$currentSupplierEmail'");
            
            if ($phoneNumberCheck && $emailCheck) {
                if (mysqli_num_rows($phoneNumberCheck) > 0 && mysqli_fetch_assoc($phoneNumberCheck)['id'] != $currentSupplierId) {
                    redirect("../supplier/viewSupplier.php?Id=<?=$currentSupplierId;?>", 'Phone already used by another supplier account');
                }
                if (mysqli_num_rows($emailCheck) > 0 && mysqli_fetch_assoc($emailCheck)['id'] != $currentSupplierId) {
                    redirect("../supplier/viewSupplier.php?Id=<?=$currentSupplierId;?>", 'Email already used by another supplier account');
                }
            }

            $supplier_updated_data = [
                'name' => $currentSupplierName,
                'phone_number' => $currentSupplierPhoneNumber,
                'email' => $currentSupplierEmail,
            ];

            $result = update('suppliers', $currentSupplierId, $supplier_updated_data);
            if ($result) {
                redirect('../supplier/viewSupplier.php', 'Supplier updated successfully');
            } else {
                redirect('../supplier/viewSupplier.php', 'Something went wrong');
            }
        }
    }
}

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
include('../supplier/header.html');
?>

    <div class="main--content">
    <?php alertMessage(); ?>
        <form action="../supplier/addSupplier.php" method="POST">
            <div class="header--wrapper">
                <div class="header--title">
                    <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                    <span>Suppliers</span>
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
                        <span>Add Supplier</span>
                    </li>
                    <?php } ?>
                    <li class="supplier-item">
                        <a href="../supplier/viewSupplier.php">
                            <span>View Supplier</span>
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
                        <input type="text" name="supplier_name" placeholder="Enter supplier's name" onblur="validate(2)">
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Phone Number
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="text" name="supplier_phone_number" placeholder="Enter supplier's phone number" onblur="validate(4)">
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Email
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="email" name="supplier_email" placeholder="Enter supplier's email" onblur="validate(3)">
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="form-group col-sm-6">
                        <button name="saveSupplier" type="submit" class="btn-block btn-primary">Add Supplier</button>
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