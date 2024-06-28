<?php 
    session_start();
    include('../include/databaseHandler.inc.php');
    include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Design</title>
    <!--Font for icon (cdn link)-->
    <!-- Boxicons JS -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

            <!-- Title -->
        <div class="supplier-items-container">
            <ul class="supplier-menu">
                <li class="supplier-item">
                    <a href="invoiceForm.php">
                        <span>Generate Invoices</span>
                    </a>
                </li>
                <li class="supplier-item active">
                    <span>View Invoices</span>
                </li>
            </ul>
        </div>

        <div class="sp--wrapper">
            <div class="row">
                <div class="col-12 mb-3 mb-lg-5">
                    <div class="position-relative card table-nowrap table-card">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Date</th>
                                        <th>Pharmacist</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $checkInvoice = mysqli_query($conn, "SELECT * FROM invoices");
                                        if ($checkInvoice && mysqli_num_rows($checkInvoice) > 0) {
                                            while ($row = mysqli_fetch_assoc($checkInvoice)) {
                                                $pharmacist = mysqli_query($conn, "SELECT * FROM users WHERE Id = '{$row['Id_pharmacist']}' LIMIT 1");
                                                $customer = mysqli_query($conn, "SELECT * FROM users WHERE Id = '{$row['Id_customer']}' LIMIT 1");
                                                $pharmacistName = mysqli_fetch_assoc($pharmacist);
                                                $customerName = mysqli_fetch_assoc($customer);

                                            }

                                        }
        
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
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