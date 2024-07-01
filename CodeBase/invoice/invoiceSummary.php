<?php 
    session_start();
    // require_once '../include/config_session.inc.php';
    require_once '../include/databaseHandler.inc.php';
    include('header.php');
    if (isset($_SESSION['user_username'])) {
        if(!isset($_SESSION['medicineItems'])){
            header("Location: invoiceForm.php");
            exit();
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Form</title>
    <link rel="stylesheet" href="style.css">
    <!--Font for icon (cdn link)-->
    <!-- Boxicons JS -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700" rel='stylesheet' type='text/css'>


    <style>
    @media print {
        /* Hide all elements by default */
        head, body * {
            visibility: hidden !important;
        }

        /* Specifically hide the title */
        title {
            display: none !important;
        }

        /* Print the main content */
        .main--content, .main--content * {
            visibility: visible !important;
        }

        /* Ensure the main content takes up the full width */
        .main--content {
            width: 100% !important;
            position: static !important;
            left: 0 !important;
            top: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Style the card elements within the main content */
        .main--content .card {
            position: static !important; /* Use static to avoid overlap */
            width: 100% !important; /* Ensure cards take up full width */
            border: none !important;
            box-shadow: none !important;
            margin-top: 50px !important; /* Add some spacing between cards */
            margin-bottom: 10px !important; /* Add some spacing between cards */
            margin-left: 0px !important; /* Add some spacing between cards */
            margin-right: 0px !important; /* Add some spacing between cards */
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .main--content .card div, .main--content .card span, .main--content .card p, .main--content .card h1, .main--content .card h2, .main--content .card h3, .main--content .card h4, .main--content .card h5, .main--content .card h6 {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .main--content table, .main--content td, .main--content th {
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        .main--content .item-description {
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        /* Hide the header and footer if they exist */
        .sidebar, .header--wrapper, .supplier-items-container, .supplier-menu, .supplier-item, .logout, .header, .footer{
            display: none !important;
        }

        /* Ensure the header is properly positioned */
        .header {
            visibility: visible !important;
            position: relative !important;
        }

        /* Adjust any specific elements that may need special handling */
        .header, .footer {
            visibility: hidden !important;
        }

        /* Ensure no excessive margins or padding */
        body, .main--content, .row, .col-lg-12, .card, .card-body, .invoice-title, .invoice-title h4, .invoice-title h2, .text-muted, .text-muted p{
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
    
</head>

<body>
    <div class="main--content">

        <div class="container">
            <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="invoice-title">
                                    <!-- <h4 class="float-end font-size-15">Invoice #DZ0112 <span class="badge bg-success font-size-12 ms-2"></span></h4> -->
                                    <div class="mb-4">
                                       <h2 class="mb-1 text-muted">Appotheke.com</h2>
                                    </div>
                                    <div class="text-muted">
                                        <p class="mb-1">Ring Road 4 Street Quarter 4 Ben Cat Town, Binh Duong</p>
                                        <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i>appotheke@manager.com</p>
                                        <p><i class="uil uil-phone me-1"></i>012-345-6789</p>
                                    </div>
                                </div>
            
                                <hr class="my-4">
            
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="text-muted">
                                            <h5 class="font-size-16 mb-3">Billed To:</h5>
                                            <h5 class="font-size-15 mb-2">
                                                <?php
                                                    $cphone = $_SESSION['cphone'];
                                                    $customerQuery = mysqli_query($conn, "SELECT * FROM customers WHERE phone_number='$cphone' LIMIT 1");
                                                    if ($customerQuery && mysqli_num_rows($customerQuery) > 0) {
                                                        $row = mysqli_fetch_assoc($customerQuery);
                                                        echo $row['Name'];
                                                    } else {
                                                        '<h5>No Customer Found</h5>';
                                                        return;
                                                    }
                                                ?>
                                            </h5>
                                            <p><?php echo $_SESSION['cphone'] ?></p>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="text-muted text-sm-end">
                                            <div>
                                                <h5 class="font-size-15 mb-1">Invoice No:</h5>
                                                <p>
                                                    <?php
                                                        $getLastInvoiceId = mysqli_query($conn, "SELECT * FROM sales ORDER BY Id DESC LIMIT 1");
                                                        if ($getLastInvoiceId && mysqli_num_rows($getLastInvoiceId) > 0) {
                                                            $row = mysqli_fetch_assoc($getLastInvoiceId);
                                                            echo $row['Id'] + 1;
                                                        } else {
                                                            echo '1';
                                                        }
                                                    ?>
                                                    </p>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Invoice Date:</h5>
                                                <?php
                                                //Set default datetime zone
                                                date_default_timezone_set('Asia/Ho_Chi_Minh');    
                                                echo date('d-m-y');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                                
                                <div class="py-2">
                                    <h5 class="font-size-15">Order Summary</h5>
            
                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 70px;">No.</th>
                                                    <th>Item</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th class="text-end" style="width: 120px;">Sub Total</th>
                                                </tr>
                                            </thead><!-- end thead -->
                                            <?php 
                                                foreach ($_SESSION['medicineItems'] as $key => $item) {
                                                    $subTotal = $item['Price'] * $item['quantity'];
                                                    echo '
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">'.($key + 1).'</th>
                                                                <td>
                                                                    <div>
                                                                        <h5 class="text-truncate font-size-14 mb-1">'.$item['medicine_name'].'</h5>
                                                                        <p class="text-muted mb-0">'.$item['Type'].'</p>
                                                                    </div>
                                                                </td>
                                                                <td>$ '.$item['Price'].'</td>
                                                                <td>'.$item['quantity'].'</td>
                                                                <td class="text-end">$ '.$subTotal.'</td>
                                                            </tr>
                                                        </tbody>';
                                                }
                                                //Total of the invoice
                                                $total = 0;
                                                foreach ($_SESSION['medicineItems'] as $item) {
                                                    $total += $item['Price'] * $item['quantity'];
                                                }
                                                echo '
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td class="text-end">Total</td>
                                                            <td class="text-end">$ '.$total.'</td>
                                                        </tr>
                                                    </tfoot>';

                                            ?>
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->
                                    <?php if(isset($_SESSION['medicineItems'])) :?> 
                                        <div class="d-print-none mt-4">
                                            <div class="float-end">
                                                <a href="invoiceForm.php" class="btn btn-danger w-md">Back</a>                                            
                                                <a id="saveInvoice" href="javascript:window.print()" class="btn btn-success me-1"><i class="fa fa-print">Save Order and Print</i></a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
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
    include('footer.html');  
?>