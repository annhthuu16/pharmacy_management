<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - Appotheke</title>
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
            <li class="active">
                <a href="../medicine/addMedicine.php">
                    <i class='bx bx-capsule' ></i>
                    <span>Inventory</span>
                </a>
            </li>
            <?php }
            else {?>
            <li  class="active"><a href="../medicine/viewMedicine.php"><i class='bx bx-capsule' ></i><span>Inventory</span></a></li>
            <?php } ?> 

            <li><a href="../customer/addCustomer.php"><i class='bx bx-street-view'></i><span>Customers</span></a></li>
            
            <li><a href="../invoice/invoiceForm.php"><i class='bx bx-credit-card' ></i><span>Invoices</span></a></li>

            <li><a href="../newChat/chat.php"><i class='bx bx-conversation' ></i><span>Messages</span></a></li>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?> 
            <li><a href="../employee/viewPharmacist.php"><i class='bx bx-group' ></i><span>Employee</span></a></li>

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