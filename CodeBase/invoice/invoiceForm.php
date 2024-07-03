<?php
    require_once('../include/function.php');
    require_once('../include/databaseHandler.inc.php');
    if (isset($_SESSION['user_username'])){
        include('header.php');
?>


<!-- Modal to add new customer if phone number is not recognized -->
<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mt-3">
            <label for="c_name">Enter Customer Name</label>
            <input type="text" id="c_name" class="form-control" />
        </div>
        <div class="mt-3">
            <label for="c_phone">Enter Customer's phone number</label>
            <input type="text" id="c_phone" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="saveCustomerBtn" type="button" class="btn btn-primary saveCustomer">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Invoice Form starts here-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Invoice Form</h4>
                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>
                    <form action="invoiceFormHandler.php" method="POST">
                        <div class="main-form mt-4 border-bottom">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="medicine_name">Select Medicine</label>
                                    <select id="medicine_name" name="medicine_name[]" class="form-select mySelect2" required>
                                        <option value="">Select medicine</option>
                                        <?php
                                            if ($conn === false) {
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }
                                            $sql = "SELECT Id, medicine_name FROM medicines";
                                            $result = mysqli_query($conn, $sql);
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($medicineItem = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $medicineItem['Id'] . '">' . $medicineItem['medicine_name'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No medicine found.</option>';
                                            }
                                            mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-2">
                                        <label for="quantity">Quantity</label>
                                        <input id="quantity" type="number" name="quantity[]" class="form-control" required placeholder="Enter Quantity" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save_multiple_data" class="btn btn-primary">Add Item to Invoice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // print_r($_SESSION['medicineItems']); 
?>

<div class="col-md-12">
        <div class = "card mt-4">
            <div class = "card-header">
                <h4>Medicines</h4>
            </div>
            <div class="card-body">
            <?php
                if (isset($_SESSION['medicineItems']) && !empty($_SESSION['medicineItems'])) {
                    $sessionMedicines = $_SESSION['medicineItems'];
                    ?>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#NO</th>
                                            <th>Medicine Name</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th>ID Supplier</th>
                                            <th>Quantity</th>
                                            <th>Expiry date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sessionMedicines as $key => $item) : ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $item['medicine_name']; ?></td>
                                                <td><?= number_format($item['Price'] * $item['quantity'],0); ?></td>
                                                <td><?= $item['Type']; ?></td>
                                                <td><?= $item['Id_supplier']; ?></td>
                                                <td>
                                                    <div class="input-group qtyBox">
                                                        <button class="input-group-text decrement">-</button>
                                                        <input type="text" value="<?=$item['quantity']; ?>" class="qty quantityInput">
                                                        <button class="input-group-text increment">+</button>
                                                    </div>
                                                </td>
                                                <td><?= $item['expiry_date']; ?></td>
                                                <td>
                                                    <a href="invoiceItemDelete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div> 

                            <div class="mt-2">
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Select Payment Method</label>
                                        <select id="payment_mode" class="form-select">
                                            <option value="">--Select Payment--</option>
                                            <option value="Cash Payment">Cash Payment</option>
                                            <option value="Online Payment">Online Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Enter Customer Phone Number</label>
                                        <input type="text" id="cphone" class="form-control" />

                                    </div>
                                    <div class="col-md-4">
                                        <br/>
                                        <button type="button" id="proceedToPlaceBtn" class="btn btn-warning w-100 proceedToPlace">Proceed to place order</button>
                                    </div>
                                </div>
                            </div>
                        <?php
                            } else {
                                echo '<h5> No Items Added </h5>';
                            }
                        ?>                   
            </div>
        </div>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
<?php
    include('footer.html');  
    }else {
        header("Location: ../login/index.php");
    }
?>