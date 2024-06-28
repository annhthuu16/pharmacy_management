<?php 
    include('../include/databaseHandler.inc.php');
    include('../include/function.php');
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
                    <a href="../invoice/invoiceForm.php">
                        <span>Generate Invoices</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>View Invoices</span>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="sp--wrapper">
                <?php 
                    alertMessage(); 
                    
                    $searchQuery = "";
                    if (isset($_GET['search'])) {
                        $search = validate($_GET['search']);
                        $searchQuery = " WHERE CONCAT(Pharmacist_Id, Customer_Id, payment_mode, Total, Date) LIKE '%$search%'";
                    }

                    $invoices = getSearch('sales', $searchQuery);
                    if (!$invoices) {
                        echo '<h4>Something went wrong.</h4>';
                        return false;
                    }

                    if (mysqli_num_rows($invoices) > 0) {
                ?>
                <div class="row">
                    <div class="col-12 mb-3 mb-lg-5">
                        <div class="position-relative card table-nowrap table-card">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
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
                                        <?php foreach ($invoices as $key => $invoice) : ?>
                                        <tr class="align-middle">
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $invoice['Pharmacist_Id']; ?></td>
                                            <td><?= $invoice['Customer_Id']; ?></td>
                                            <td><?= $invoice['payment_mode']; ?></td>
                                            <td><?= $invoice['Total']; ?></td>
                                            <td><?= $invoice['Date']; ?></td>
                                        
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        } else {
            echo '<h4>No invoice found.</h4>';
        }

        include('../include/footer.html');
        ?>

</body>
</html>