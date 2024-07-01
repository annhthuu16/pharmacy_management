<?php
require('../include/function.php');
include('../include/databaseHandler.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['saveCustomer'])) {
        $customer_name = validate($_POST['customer_name']);
        $customer_phone_number = validate($_POST['customer_phone_number']);

        if ($customer_name != '' && $customer_phone_number != '') {
            $customer_data = [
                'Name' => $customer_name,
                'phone_number' => $customer_phone_number,
            ];

            $result = insert('customers', $customer_data);
            if ($result) {
                redirect('../customer/addCustomer.php', 'Customer created successfully');
            } else {
                redirect('../customer/addCustomer.php', 'Something went wrong');
            }
        } else {
            redirect('../customer/addCustomer.php', 'Please fill all required fields');
        }
    }

    if (isset($_POST['updateCustomer'])) {
        $currentCustomerId = validate($_POST['currentCustomerId']);
        $currentCustomerName = validate($_POST['currentCustomerName']);
        $currentCustomerPhoneNumber = validate($_POST['currentCustomerPhoneNumber']);

        if ($currentCustomerName != '' && $currentCustomerPhoneNumber != '') {
            $customer_updated_data = [
                'Name' => $currentCustomerName,
                'phone_number' => $currentCustomerPhoneNumber,
            ];

            $result = update('customers', $currentCustomerId, $customer_updated_data);
            if ($result) {
                redirect('../customer/viewCustomer.php', 'Customer updated successfully');
            } else {
                redirect('../customer/viewCustomer.php', 'Something went wrong');
            }
        } else {
            redirect('../customer/addCustomer.php', 'Please fill all required fields');
        }
    }
}

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username'])) {
include('../customer/header.php');
?>

<div class="main--content">
    <?php alertMessage(); ?>
    <form action="../customer/addCustomer.php" method="POST">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Customers</span>
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
                    <span>Add Customers</span>
                </li>
                <?php } ?>
                <li class="supplier-item">
                    <a href="../customer/viewCustomer.php">
                        <span>View Customers</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sp--wrapper">
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Customer's Name
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="customer_name" placeholder="Enter customer's name" onblur="validate(2)">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Customer's Phone Number
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="customer_phone_number" placeholder="Enter customer's phone number" onblur="validate(4)">
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6">
                    <button name="saveCustomer" type="submit" class="btn-block btn-primary">Add Customer</button>
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