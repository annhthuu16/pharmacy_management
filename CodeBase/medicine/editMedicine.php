<?php
require('../include/function.php');
require_once('../include/databaseHandler.inc.php');
require_once('../include/signup_view.inc.php');

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
include('../medicine/header.html');    
?>

<div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <h2>Hello <?php echo $_SESSION['user_username']?></h2>
            <span>Edit Medicine</span>
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

    $currentMedicine = getById('medicines', $paramValue);
    ?>

    <form action="../medicine/addMedicine.php" method="POST">
        <div class="sp--wrapper">
            <input type="hidden" name="currentMedicineId" value="<?=$currentMedicine['Id']; ?>" required> 

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Medicine Name
                        <span class="text-danger"> *</span>
                    </label>
                    <input type="text" name="currentMedicineName" value="<?=$currentMedicine['medicine_name']; ?>" required placeholder="Enter medicine name" onblur="validate(2)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Price
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicinePrice" value="<?=$currentMedicine['Price']; ?>" required placeholder="Enter price" onblur="validate(4)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Type
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicineType" value="<?=$currentMedicine['Type']; ?>" required placeholder="Enter type" onblur="validate(3)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Supplier ID
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicineSupplierId" value="<?=$currentMedicine['Id_supplier']; ?>" required placeholder="Enter supplier ID" onblur="validate(5)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Quantity
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicineQuantity" value="<?=$currentMedicine['quantity']; ?>" required placeholder="Enter quantity" onblur="validate(6)"> 
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Expiry Date
                        <span class="text-danger"> *</span>
                    </label> 
                    <input type="text" name="currentMedicineExpiryDate" value="<?=$currentMedicine['expiry_date']; ?>" required placeholder="Enter expiry date" onblur="validate(7)"> 
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6"> 
                    <button name="updateMedicine" type="submit" class="btn-block btn-primary">Update Medicine</button> 
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
