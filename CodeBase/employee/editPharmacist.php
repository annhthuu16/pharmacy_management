<?php
require('../include/function.php');
require_once('../include/databaseHandler.inc.php');

// session_start();
require_once('../include/config_session.inc.php');

if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['savePharmacist']) || isset($_POST['updatePharmacist'])) {
            $username = validate($_POST['Username'] ?? $_POST['currentUsername']);
            $password = validate($_POST['Pwd'] ?? $_POST['currentPassword']);
            $fullname = validate($_POST['FullName'] ?? $_POST['currentFullname']);
            $email = validate($_POST['Email'] ?? $_POST['currentEmail']);
            $role = validate($_POST['Role'] ?? $_POST['currentRole']);
            $isUpdate = isset($_POST['updatePharmacist']);
            $currentPharmacistId = $isUpdate ? validate($_POST['currentPharmacistId']) : null;

            if ($username != '' && $password != '' && $fullname != '' && $email != '' && $role != '') {
                $usernameCheck = mysqli_query($conn, "SELECT * FROM pharmacists WHERE Username='$username'");
                $emailCheck = mysqli_query($conn, "SELECT * FROM pharmacists WHERE Email='$email'");
                
                if ($usernameCheck && $emailCheck) {
                    if (mysqli_num_rows($usernameCheck) > 0) {
                        $existingUsername = mysqli_fetch_assoc($usernameCheck);
                        if (!$isUpdate || $existingUsername['Id'] != $currentPharmacistId) {
                            redirect('../employee/editPharmacist.php', 'Username already used by another pharmacist account');
                        }
                    }
                    if (mysqli_num_rows($emailCheck) > 0) {
                        $existingEmail = mysqli_fetch_assoc($emailCheck);
                        if (!$isUpdate || $existingEmail['Id'] != $currentPharmacistId) {
                            redirect('../employee/editPharmacist.php', 'Email already used by another pharmacist account');
                        }
                    }
                }

                $pharmacist_data = [
                    'Username' => $username,
                    'Pwd' => $password,
                    'FullName' => $fullname,
                    'Email' => $email,
                    'Role' => $role,
                ];

                if ($isUpdate) {
                    $result = update('pharmacists', $currentPharmacistId, $pharmacist_data);
                    $message = $result ? 'Pharmacist updated successfully' : 'Something went wrong';

                    // Update session variable if the current user is updated
                    if ($result && $_SESSION['user_username'] == $_POST['currentUsername']) {
                        $_SESSION['user_username'] = $username;
                    }
                } else {
                    $result = insert('pharmacists', $pharmacist_data);
                    $message = $result ? 'Pharmacist created successfully' : 'Something went wrong';
                }

                redirect('../employee/editPharmacist.php', $message);
            } else {
                redirect('../employee/editPharmacist.php', 'Please fill all required fields');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System</title>
    <link rel="stylesheet" href="view_style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Sidebar Start -->
    <div class="sidebar">
        <div class="logo">
            <img src="../image/Logo.png" alt="Logo">
            <span class="brand-name">APPOTHEKE</span>
        </div>
        <ul class="menu">
            <li><a href="../dashboard/index.php"><i class='bx bxs-dashboard'></i><span>Dashboard</span></a></li>
            <li><a href="../signup/index.php"><i class='bx bx-user'></i><span>Create Account</span></a></li>
            <li><a href="../sale/addSale.php"><i class='bx bx-line-chart'></i><span>Sale</span></a></li>
            <li><a href="../supplier/addSupplier.php"><i class='bx bx-package'></i><span>Suppliers</span></a></li>
            <li><a href="../medicine/addMedicine.php"><i class='bx bxs-capsule'></i><span>Inventory</span></a></li>
            <li><a href="../customer/addCustomer.php"><i class='bx bx-cog'></i><span>Customers</span></a></li>
            <li><a href="../invoice/invoiceForm.php"><i class='bx bx-credit-card'></i><span>Invoices</span></a></li>
            <li><a href="../chat/src/App.jsx"><i class='bx bx-conversation'></i><span>Messages</span></a></li>
            <li class="active"><a href="#"><i class='bx bx-user-plus'></i><span>Employee</span></a></li>
            <li class="logout"><a href="#"><i class='bx bx-log-out bx-rotate-180'></i><span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main--content">
        <?php alertMessage(); ?>
        <form action="../employee/editPharmacist.php" method="POST">
            <div class="header--wrapper">
                <div class="header--title">
                    <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                    <span>Pharmacists</span>
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
            <div clas="supplier-items-container">
                <ul class="supplier-menu">
                    <?php if (isset($_SESSION['user_username'])) { ?>
                    <li class="supplier-item active">
                        <span><?php echo isset($_GET['Id']) ? 'Edit Pharmacist' : 'Save Pharmacist'; ?></span>
                    </li>
                    <?php } ?>
                    <li class="supplier-item">
                        <a href="../employee/viewPharmacist.php">
                            <span>View Pharmacist</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="ph--wrapper">
                <?php
                $isEdit = isset($_GET['Id']);
                if ($isEdit) {
                    $paramValue = checkParamId('Id');
                    if (!is_numeric($paramValue)) {
                        echo '<h5>' . $paramValue . '</h5>';
                        return false;
                    }

                    $currentPharmacist = getById('pharmacists', $paramValue);
                }
                ?>

                <?php if ($isEdit) { ?>
                <input type="hidden" name="currentPharmacistId" value="<?=$currentPharmacist['Id']; ?>" required>
                <?php } ?>

                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Username
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="text" name="<?php echo $isEdit ? 'currentUsername' : 'Username'; ?>" value="<?php echo $isEdit ? $currentPharmacist['Username'] : ''; ?>" placeholder="Enter pharmacist's username" required>
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Password
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="text" name="<?php echo $isEdit ? 'currentPassword' : 'Pwd'; ?>" value="<?php echo $isEdit ? $currentPharmacist['Pwd'] : ''; ?>" placeholder="Enter pharmacist's password" required>
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Full Name
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="text" name="<?php echo $isEdit ? 'currentFullname' : 'FullName'; ?>" value="<?php echo $isEdit ? $currentPharmacist['FullName'] : ''; ?>" placeholder="Enter pharmacist's full name" required>
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Email
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="email" name="<?php echo $isEdit ? 'currentEmail' : 'Email'; ?>" value="<?php echo $isEdit ? $currentPharmacist['Email'] : ''; ?>" placeholder="Enter pharmacist's email" required>
                    </div>
                </div>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label px-3">Role
                            <span class="text-danger"> *</span>
                        </label>
                        <select name="<?php echo $isEdit ? 'currentRole' : 'Role'; ?>" required>
                            <option value="">Select Role</option>
                            <option value="Admin" <?php echo ($isEdit && $currentPharmacist['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Pharmacist" <?php echo ($isEdit && $currentPharmacist['Role'] == 'Pharmacist') ? 'selected' : ''; ?>>Pharmacist</option>
                        </select>
                    </div>
                </div>

                <div class="row justify-content-between text-left mt-4">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <button type="submit" name="<?php echo $isEdit ? 'updatePharmacist' : 'savePharmacist'; ?>" class="btn btn-primary">
                            <?php echo $isEdit ? 'Update Pharmacist' : 'Save Pharmacist'; ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>
