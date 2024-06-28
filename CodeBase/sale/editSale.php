<?php
require('../include/function.php');
require_once('../include/databaseHandler.inc.php');
require_once('../include/signup_view.inc.php');

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
include('../sale/header.html');    
?>

<div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <h2>Hello <?php echo $_SESSION['user_username']?></h2>
            <span>Edit Sale</span>
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
    $paramValue = checkParamId('Id');
    if (!is_numeric($paramValue)) {
        echo '<h5>' . $paramValue . '</h5>';
        return false;
    }

    $currentSale = getById('sales', $paramValue);
    ?>

    <form action="../sale/addSale.php" method="POST">
        <div class="sp--wrapper">
            <input type="hidden" name="currentSaleId" value="<?=$currentSale['Id']; ?>" required> 

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Pharmacist ID
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="currentPharmacistId" value="<?=$currentSale['Id_pharmacist']; ?>" required placeholder="Enter pharmacist ID" onblur="validate(2)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Customer ID
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentCustomerId" value="<?=$currentSale['Id_customer']; ?>" required placeholder="Enter customer ID" onblur="validate(4)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Medicine ID
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicineId" value="<?=$currentSale['Id_medicine']; ?>" required placeholder="Enter medicine ID" onblur="validate(3)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Time
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentTime" value="<?=$currentSale['time']; ?>" required placeholder="Enter time" onblur="validate(5)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Quantity
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentQuantity" value="<?=$currentSale['quantity']; ?>" required placeholder="Enter quantity" onblur="validate(6)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Total
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentTotal" value="<?=$currentSale['total']; ?>" required placeholder="Enter total" onblur="validate(7)"> 
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6"> 
                    <button name="updateSale" type="submit" class="btn-block btn-primary">Update Sale</button> 
                </div>
            </div>
        </div>
    </form>
</div>        

<?php include('../include/footer.html'); ?>

</body>
</html>
<?php
    }else {
        header("Location: ../login/index.php");
    }
?>
