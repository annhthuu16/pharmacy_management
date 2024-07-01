<?php 
    include('../include/databaseHandler.inc.php');
    include('../include/function.php');
    if (isset($_SESSION['user_username'])){
        include('header.php');?>
<!-- Title -->
<div class="supplier-items-container">
    <ul class="supplier-menu">
        <?php if (isset($_SESSION['user_username'])) { ?>
            <li class="supplier-item active">
                <a>
                    <span>View Sales</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
<body>

        <div class="sp--wrapper">
                <?php 
                    alertMessage(); 
                    
                    // $searchQuery = "";
                    // if (isset($_GET['search'])) {
                    //     $search = validate($_GET['search']);
                    //     $searchQuery = " WHERE CONCAT(Pharmacist_Id, Customer_Id, payment_mode, Total, Date) LIKE '%$search%'";
                    // }

                    // $invoices = getSearch('sales', $searchQuery);
                    // if (!$invoices) {
                    //     echo '<h4>Something went wrong.</h4>';
                    //     return false;
                    // }
                    $invoices = mysqli_query($conn, "SELECT *, s.Id AS invoice_id,c.phone_number AS c_phone_number FROM sales s,pharmacists p,customers c WHERE s.Pharmacist_Id = p.Id AND s.Customer_Id = c.Id");
                    
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
                                        <th>Pharmacist ID</th>
                                        <th>Pharmacist Name</th>
                                        <th>Customer</th>
                                        <th>Customer's Phone</th>
                                        <th>Payment Mode</th>
                                        <th>Total</th>
                                        <th>Expiry date</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($invoices as $key => $invoice) : ?>
                                        <tr class="align-middle">
                                            <td><?= $key + 1; ?></td>
                                            <td><?php echo date('d-m-y'); ?></td>
                                            <td><?= $invoice['Pharmacist_Id']; ?></td>
                                            <td><?= $invoice['FullName']; ?></td>
                                            <td><?= $invoice['Customer_Id']; ?></td>
                                            <td><?= $invoice['c_phone_number']; ?></td>
                                            <td><?= $invoice['payment_mode']; ?></td>
                                            <td><?= $invoice['Total']; ?></td>
                                            <td ><?= $invoice['Date']; ?></td>
                                            <td>
                                            <div class="btn-group" role="group">
                                                <a href="invoiceDetails.php?Id=<?= $invoice['invoice_id']; ?>" class="btn btn-primary">View Details</a>
                                            </div>
                                        </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="align-middle"> 
                                                <strong>Total Revenue</strong>
                                            </td>
                                            <td class="align-middle">
                                                <?php 
                                                    $total = 0;
                                                    $calculateRevenue = mysqli_query($conn, "SELECT SUM(Total) AS total FROM sales");
                                                    $total = mysqli_fetch_assoc($calculateRevenue);
                                                    echo $total['total'];
                                                ?>
                                            </td>
                                        </tr>
                                    </tfoot>
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
        }else {
            header("Location: ../login/index.php");
        }
?>

</body>
</html>