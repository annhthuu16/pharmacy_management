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
            <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item">
                    <a href="viewSale.php">
                        <span>View Sales</span>
                    </a>
                </li>
                <li class="supplier-item active">
                    <?php
                    $paramResult = checkParamId('Id');
                    if (is_numeric($paramResult)){
                        $indexValue = validate($paramResult);
                    }
                    ?>
                    <span>Invoices No #<?php echo $indexValue; ?> Details</span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="sp--wrapper">
        <?php 
            alertMessage(); 
            
            if(isset($_SESSION['user_username'])){
                $paramResult = checkParamId('Id');
                if (is_numeric($paramResult)){
                    $indexValue = validate($paramResult);
                    // $query = "SELECT * FROM invoice_medicines WHERE Invoice_Id = '$indexValue'";
                    $query = "SELECT *, i.quantity AS sub_quantity FROM invoice_medicines i, sales s, customers c, medicines m WHERE i.Invoice_Id = '$indexValue' AND i.Invoice_Id = s.Id AND s.Customer_Id = c.Id AND i.Medicine_Id = m.Id";
                    $checkInvoiceDetail = mysqli_query($conn, $query);
                    
                    if ($checkInvoiceDetail === false) {
                        echo "Error in query: " . mysqli_error($conn);
                    } else if (mysqli_num_rows($checkInvoiceDetail) > 0) {
                        $invoiceDetails = mysqli_fetch_all($checkInvoiceDetail, MYSQLI_ASSOC);
                    } else {
                        redirect('viewSale.php', 'Invoice not found.');
                    }

                }
            } else {
                header("Location: ../login/index.php");
            }

            if (!empty($invoiceDetails)) {
        ?>
        <div class="row">
            <div class="col-12 mb-3 mb-lg-4">
                <div class="position-relative card table-nowrap table-card">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th>Item ID</th> -->
                                    <th>Invoice ID</th>
                                    <th>Medicine Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoiceDetails as $key => $invoiceDetail) : ?>
                                <tr class="align-middle">
                                    <!-- <td><?= $invoiceDetail['Id']; ?></td> -->
                                    <td><?= $invoiceDetail['Invoice_Id']; ?></td>
                                    <td><?= $invoiceDetail['medicine_name']; ?></td>
                                    <td><?= $invoiceDetail['Price']; ?></td>
                                    <td><?= $invoiceDetail['sub_quantity']; ?></td>
                                    <td>
                                </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
