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

            $pharmacist_data = [
                'Username' => $username,
                'Pwd' => $password,
                'FullName' => $fullname,
                'Email' => $email,
                'Role' => $role,
            ];

            if ($isUpdate) {
                $result = update('pharmacists', $currentPharmacistId, $pharmacist_data);
                header("Location: ../include/logout.inc.php");
                exit();

                // Update session variable if the current user is updated
                if ($result) {
                    if ($_SESSION['user_username'] == $_POST['currentUsername']) {
                        $_SESSION['user_username'] = $username;
                    }
                }
                
            } else {
                $result = insert('pharmacists', $pharmacist_data);
                if ($result) {
                    // If pharmacist is created successfully, log out the user
                    header("Location: ../include/logout.inc.php");
                    exit();
                } else {
                    $message = 'Something went wrong';
                }
            }

            redirect('../employee/editPharmacist.php', $message);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - Appotheke</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <style>
        body {
            font-size: 1.2em; /* Increase the overall font size */
        }
        .form-group label {
            font-size: 1.2em; /* Increase the label font size */
        }
        .form-group input {
            font-size: 1.2em; /* Increase the input font size */
            height: 50px; /* Increase the height of the input fields */
        }
        .btn-primary {
            font-size: 1.2em; /* Increase the button font size */
            height: 50px; /* Increase the height of the button */
        }
    </style>

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
            
            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../signup/index.php"><i class='bx bx-user-plus'></i><span>Create Account</span></a></li>
            <?php }?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../sale/viewSale.php"><i class='bx bx-line-chart' ></i><span>Sale</span></a></li>
            <?php }?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../supplier/addSupplier.php"><i class='bx bx-package'></i><span>Suppliers</span></a></li>
            <?php } ?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?> 
            <li><a href="../medicine/addMedicine.php"><i class='bx bxs-capsule' ></i><span>Inventory</span></a></li>
            <?php }
            else {?>
            <li><a href="../medicine/viewMedicine.php"><i class='bx bx-capsule' ></i><span>Inventory</span></a></li>
            <?php } ?> 

            <li><a href="../customer/addCustomer.php"><i class='bx bx-street-view'></i><span>Customers</span></a></li>
            
            <li><a href="../invoice/invoiceForm.php"><i class='bx bx-credit-card' ></i><span>Invoices</span></a></li>

            <li><a href="../newChat/chat.php"><i class='bx bx-conversation' ></i><span>Messages</span></a></li>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?> 
            <li  class="active"><a href="../employee/viewPharmacist.php"><i class='bx bx-group' ></i><span>Employee</span></a></li>

            <?php } ?>
            <li class="logout">
                <form action="../include/logout.inc.php" method="post">
                    <button type="submit" name="logout-submit" class="logout">
                        <a href="#">
                            <i class='bx bx-log-out bx-rotate-180' ></i>
                            <span>Logout</span>
                        </a>
                    </button>
                </form>
            </li>
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
                            <?php echo $isEdit ? 'Update' : 'Save'; ?>
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
    header("Location: ../login/index.php");
    exit();
}
?>
