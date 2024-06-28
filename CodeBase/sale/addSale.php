<?php
require('../include/function.php');
include('../include/databaseHandler.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['saveSale'])) {
        $id_pharmacist = validate($_POST['Id_pharmacist']);
        $id_customer = validate($_POST['Id_customer']);
        $id_medicine = validate($_POST['Id_medicine']);
        $time = validate($_POST['time']);
        $quantity = validate($_POST['quantity']);
        $total = validate($_POST['total']);

        if ($id_pharmacist != '' && $id_customer != '' && $id_medicine != '' && $time != '' && $quantity != '' && $total != '') {
            $sale_data = [
                'Id_pharmacist' => $id_pharmacist,
                'Id_customer' => $id_customer,
                'Id_medicine' => $id_medicine,
                'time' => $time,
                'quantity' => $quantity,
                'total' => $total,
            ];

            $result = insert('sales', $sale_data);
            if ($result) {
                redirect('../sale/addSale.php', 'Sale added successfully');
            } else {
                redirect('../sale/addSale.php', 'Something went wrong');
            }
        } else {
            redirect('../sale/addSale.php', 'Please fill all required fields');
        }
    }

    if (isset($_POST['updateSale'])) {
        $currentSaleId = validate($_POST['currentSaleId']);
        $id_pharmacist = validate($_POST['Id_pharmacist']);
        $id_customer = validate($_POST['Id_customer']);
        $id_medicine = validate($_POST['Id_medicine']);
        $time = validate($_POST['time']);
        $quantity = validate($_POST['quantity']);
        $total = validate($_POST['total']);

        if ($id_pharmacist != '' && $id_customer != '' && $id_medicine != '' && $time != '' && $quantity != '' && $total != '') {
            $sale_updated_data = [
                'Id_pharmacist' => $id_pharmacist,
                'Id_customer' => $id_customer,
                'Id_medicine' => $id_medicine,
                'time' => $time,
                'quantity' => $quantity,
                'total' => $total,
            ];

            $result = update('sales', $currentSaleId, $sale_updated_data);
            if ($result) {
                redirect('../sale/viewSale.php', 'Sale updated successfully');
            } else {
                redirect('../sale/viewSale.php', 'Something went wrong');
            }
        } else {
            redirect("../sale/viewSale.php?Id=<?=$currentSaleId;?>", 'Please fill all required fields');
        }
    }
}

require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
include('../sale/header.html');
?>

<div class="main--content">
    <?php alertMessage(); ?>
    <form action="../sale/addSale.php" method="POST">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Sale</span>
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
                    <span>Add Sale</span>
                </li>
                <?php } ?>
                <li class="supplier-item">
                    <a href="../sale/viewSale.php">
                        <span>View Sale</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sp--wrapper">
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Pharmacist ID
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Id_pharmacist" placeholder="Enter pharmacist ID" onblur="validate(1)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Customer ID
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Id_customer" placeholder="Enter customer ID" onblur="validate(2)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Medicine ID
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="Id_medicine" placeholder="Enter medicine ID" onblur="validate(3)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Time
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="time" placeholder="Enter time (YYYY/MM/DD HH:MM:SS)" onblur="validate(4)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Quantity
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="quantity" placeholder="Enter quantity" onblur="validate(5)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Total
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="total" placeholder="Enter total price" onblur="validate(6)">
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6">
                    <button name="saveSale" type="submit" class="btn-block btn-primary">Add Sale</button>
                </div>
            </div>
        </div>
    </form>
    <?php include('../include/footer.html'); ?>
</div>
</body>
</html>
<?php
    } else {
        header("Location: ../login/index.php");
    }
?>
