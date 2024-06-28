<?php
    require('../include/function.php');
    require_once('../include/databaseHandler.inc.php');

    // session_start();
    require_once('../include/config_session.inc.php');
    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
    include('../medicine/header.html');    
?>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Edit Customers</span>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search..." />    
                </div>
                <img src="../image/img.png" alt="user-pic">
            </div>
        </div>

        <?php
            alertMessage();
        ?>

            <?php
                $paramValue = checkParamId('Id');
                if (!is_numeric($paramValue)){
                    echo '<h5>' . $paramValue . '</h5>';
                    return false;
                }

                $currentCustomer = getById('customers', $paramValue);
            ?>
        <form action="../customer/addCustomer.php" method="POST">
            <div class="sp--wrapper">
                <input type="hidden" name="currentCustomerId" value="<?=$currentCustomer['Id']; ?>" required placeholder="Enter customer's name" onblur="validate(2)"> 

                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex"> 
                        <label class="form-control-label px-3">Customer's Name
                            <span class="text-danger"> *</span>
                        </label>
                        <input type="text" name="currentCustomerName" value="<?=$currentCustomer['Name']; ?>" required placeholder="Enter customer's name" onblur="validate(2)"> 
                    </div>
            </div>
            <div class="row justify-content-between text-left">
                <!-- <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Customer's Email
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" id="email" name="customer_email" placeholder="Enter customer's email" onblur="validate(3)"> 
                </div> -->
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Customer's Phone Number
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentCustomerPhoneNumber" value="<?=$currentCustomer['phone_number']; ?>" required placeholder="Enter customer's phone number" onblur="validate(4)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6"> 
                    <button name="updateCustomer" type="submit" class="btn-block btn-primary">Update Customer</button> 
                </div>
            </div>
        </form>
    </div>        
<?php
    include('../include/footer.html');
?>


</body>
</html>
<?php
    }else {
        header("Location: ../login/index.php");
    }
?>