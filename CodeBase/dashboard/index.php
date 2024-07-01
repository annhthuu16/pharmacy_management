<?php 
    // session_start();
    include('../include/function.php');
    require_once '../include/config_session.inc.php';
    require_once '../include/databaseHandler.inc.php';
    if (isset($_SESSION['user_username'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - Appotheke</title>
    <link rel="stylesheet" href="style.css">
    <!--Font for icon (cdn link)-->
    <!-- Boxicons JS -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <!-- Sidebar Start -->
    <div class="sidebar">
        <div class="logo">
            <img src="../image/Logo.png" alt="Logo">
            <span class="brand-name">APPOTHEKE</span>
        </div>
        <ul class="menu">
            <li class="active"><a href="../dashboard/index.php"><i class='bx bxs-dashboard'></i><span>Dashboard</span></a></li>
            
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
            <li><a href="../medicine/viewMedicine.php"><i class='bx bxs-capsule' ></i><span>Inventory</span></a></li>
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

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Dashboard</span>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class='bx bx-search'></i>
                    <input type="text"
                    placeholder="Search..." />
                    
                </div>
                <img src="../image/img.png" alt="user-pic">
            </div>
        </div>

        <div class="card--container">
            <h3 class="main--title">Today's Data</h3>
            <div class="card--wrapper">
                <div class="payment--card background-green">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title"> 
                                Inventory Status
                            </span>
                            <span class="amount--value">Good</span>
                        </div>
                        <i class='bx bx-shield-plus icon icon-green'></i>
                    </div>

                </div>

                <div class="payment--card background-yellow">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                Revenue
                            </span>
                            <span class="amount--value">
                            <?php
                                $money = mysqli_query($conn, "SELECT SUM(total) as totalSum FROM sales");
                                if($money) {
                                    $result = mysqli_fetch_assoc($money);
                                    $totalMoney = $result['totalSum'];
                                    echo $totalMoney;
                                } else {
                                    echo 'Something went wrong!';
                                }
                            ?>
                            </span>
                        </div>
                        <i class='bx bx-money icon icon-yellow' ></i>
                    </div>
                    
                </div>

                <div class="payment--card background-blue">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                
                                Medicines Available
                            </span>
                            <span class="amount--value">
                                <?php
                                    $nextWeek = date('Y-m-d', strtotime('+7 days'));
                                    $medicine_available = mysqli_query($conn, "SELECT * FROM medicines WHERE expiry_date > '$nextWeek'");
                                    if($medicine_available) {
                                        if(mysqli_num_rows($medicine_available) > 0){
                                            $totalAvailable = mysqli_num_rows($medicine_available);
                                            echo $totalAvailable;
                                        } else {
                                            echo "0";
                                        }
                                    } else {
                                        echo 'Something went wrong!';
                                    }
                                ?>
                            </span>
                        </div>
                        <i class='bx bxs-capsule icon icon-blue' ></i>
                    </div>
                    
                </div>

                <div class="payment--card background-red">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                
                                Medicines Shortage
                            </span>
                            <span class="amount--value">
                            <?php
                                    $today = date('Y-m-d');
                                    $nextWeek = date('Y-m-d', strtotime('+7 days'));
                                    $medicine_shortage = mysqli_query($conn, "SELECT * FROM medicines WHERE expiry_date BETWEEN '$today' AND '$nextWeek'");
                                    if($medicine_shortage) {
                                        if(mysqli_num_rows($medicine_shortage) > 0){
                                            $totalShortage = mysqli_num_rows($medicine_shortage);
                                            echo $totalShortage;
                                        } else {
                                            echo "0";
                                        }
                                    } else {
                                        echo 'Something went wrong!';
                                    }
                                ?>
                            </span>
                        </div>
                        <i class='bx bx-alarm-exclamation icon icon-red' ></i>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="qc--wrapper">
            <div class="qccard--container">
                <div class="quick--card">
                    <div class="qc--header inventory">
                        <div class="qc--title">
                            Inventory
                        </div>
                        <div class="qc--button-container">
                            <a href="../medicine/viewMedicine.php"><button class="qc--button">View Details</button></a>
                            
                        </div>
                    </div>
                    <div class="qc--amounts">
                        <div class="qcamount--value1">
                        <span class="value"><?= getCount('medicines') ?></span>
                            <div>Total No of Medicines</div>
                        </div>
                        <div class="qcamount--value2">
                            <span class="value">
                                <?php
                                    $medicine_group = mysqli_query($conn, "SELECT DISTINCT Type FROM medicines");
                                    if($medicine_group) {
                                        if(mysqli_num_rows($medicine_group) > 0){
                                            $totalGroups = mysqli_num_rows($medicine_group);
                                            echo $totalGroups;
                                        } else {
                                            echo "0";
                                        }
                                    } else {
                                        echo 'Something went wrong!';
                                    }
                                ?> 
                            </span>
                            <div>Medicine group</div>
                        </div>
                    </div>
                </div>
        
                <div class="quick--card">
                    <div class="qc--header inventory">
                        <div class="qc--title">
                            Quick Report
                        </div>
                        <div class="qc--button-container">
                            <a href="../sale/viewSale.php">
                            <button class="qc--button">View Details</button></a>
                        </div>
                    </div>
                    <div class="qc--amounts">
                        <div class="qcamount--value1">
                            <span class="value">
                                <?php
                                    $medicine_sold_query = mysqli_query($conn, "SELECT SUM(quantity) AS total_quantity FROM invoice_medicines");
                                    if($medicine_sold_query) {
                                        $total_medicine_sold = mysqli_fetch_assoc($medicine_sold_query);
                                        if($total_medicine_sold){
                                            echo $total_medicine_sold['total_quantity'];
                                        } else {
                                            echo "0";
                                        }
                                    } else {
                                        echo 'Something went wrong!';
                                    }
                                ?>
                            </span>
                            <div>Number of medicines sold</div>
                        </div>
                        <div class="qcamount--value2">
                            <span class="value"><?= getCount('sales') ?></span>
                            <div>Invoices Generated</div>
                        </div>
                    </div>
                </div>
        
                <div class="quick--card">
                    <div class="qc--header inventory">
                        <div class="qc--title">
                            Medicine Status
                        </div>
                        <div class="qc--button-container">
                            <a href="../medicine/viewMedicine.php?filter=expired"><button class="qc--button">View Details</button></a>
                        </div>
                    </div>
                    <div class="qc--amounts">
                        <div class="qcamount--value1">
                            <span class="value">
                            <?php
                                    $zero_quantity = mysqli_query($conn, "SELECT DISTINCT Type FROM medicines WHERE quantity = 0");
                                    if($zero_quantity) {
                                        if(mysqli_num_rows($zero_quantity) > 0){
                                            $runningOut = mysqli_num_rows($zero_quantity);
                                            echo $runningOut;
                                        } else{
                                            echo "0";
                                        }  
                                    } else {
                                        return 'Something went wrong!';
                                    }
                                ?>
                            </span>
                            <div>Running out</div>
                        </div>
                        <div class="qcamount--value2">
                            <span class="value">
                                <?php
                                    $today = date('Y-m-d');
                                    $expired_type = mysqli_query($conn, "SELECT DISTINCT Type FROM medicines WHERE expiry_date < '$today'");
                                    if($expired_type) {
                                        if(mysqli_num_rows($expired_type) > 0){
                                            $expiredCount = mysqli_num_rows($expired_type);
                                            echo $expiredCount;
                                        } else{
                                            echo "0";
                                        }  
                                    } else {
                                        return 'Something went wrong!';
                                    }
                                ?>
                            </span>
                            <div>Expired</div>
                        </div>
                    </div>
                </div>
        
                <div class="quick--card">
                    <div class="qc--header inventory">
                        <div class="qc--title">
                            My Pharmacy
                        </div>
                        <div class="qc--button-container">
                            <a href="../employee/viewPharmacist.php">
                            <button class="qc--button">View Details</button></a>
                        </div>
                    </div>
                    <div class="qc--amounts">
                        <div class="qcamount--value1">
                            <span class="value"><?= getCount('suppliers') ?></span>
                            <div>Total No of Suppliers</div>
                        </div>
                        <div class="qcamount--value2">
                            <span class="value"><?= getCount('pharmacists') ?></span>
                            <div>Total No of Phamarcists</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        


<script>
    document.addEventListener("DOMContentLoaded", function() {
    let subMenu = document.getElementById("subMenu");
    function ToggleMenu(){
        subMenu.classList.toggle("open-menu");
    }
});
</script>


</body>
</html>

<?php  
    } else {
        header("Location: ../login/index.php");
        exit();
    }
?>