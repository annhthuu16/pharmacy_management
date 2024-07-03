<?php 
    include('../include/databaseHandler.inc.php');
    include('../include/function.php');
    if (isset($_SESSION['user_username'])){
        include('header.php');
?>

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

            // Pagination settings
            $results_per_page = 10;
            $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $offset = ($current_page - 1) * $results_per_page;

            // Get total number of invoices
            $total_sales_result = mysqli_query($conn, "SELECT COUNT(*) AS total_sales FROM sales");
            $total_sales_row = mysqli_fetch_assoc($total_sales_result);
            $total_sales = $total_sales_row['total_sales'];
            $total_pages = ceil($total_sales / $results_per_page);

            // Query to fetch invoices for the current page
            $invoices = mysqli_query($conn, "SELECT *, s.Id AS invoice_id, c.phone_number AS c_phone_number 
                                             FROM sales s, pharmacists p, customers c 
                                             WHERE s.Pharmacist_Id = p.Id AND s.Customer_Id = c.Id
                                             ORDER BY invoice_id DESC, s.Date DESC 
                                             LIMIT $offset, $results_per_page");

            if (mysqli_num_rows($invoices) > 0) {
        ?>
        <?php
            $dataPoints = array();

            // Query to get the medicine sales data
            $medicinesSold = mysqli_query($conn, "SELECT m.medicine_name, SUM(i.quantity) AS total_sold 
                                                  FROM invoice_medicines i, medicines m 
                                                  WHERE i.Medicine_Id = m.Id 
                                                  GROUP BY i.Medicine_Id 
                                                  ORDER BY total_sold DESC LIMIT 5");

            // Fetch the data and populate the $dataPoints array
            while($row = mysqli_fetch_assoc($medicinesSold)){
                $dataPoints[] = array("label" => $row['medicine_name'], "y" => $row['total_sold']);
            }
        ?>
        <script>
        window.onload = function() {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Most sold medicine items"
                },
                axisY: {
                    title: "Number of packets"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0.## packets",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        }
        </script>

        <br>
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

        <div class="spacer"></div>

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
                                    <th>Transaction Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoices as $key => $invoice) : ?>
                                <tr class="align-middle">
                                    <td><?= $invoice['invoice_id']; ?></td>
                                    <td><?php echo date('d-m-y', strtotime($invoice['Date'])); ?></td>
                                    <td><?= $invoice['Pharmacist_Id']; ?></td>
                                    <td><?= $invoice['FullName']; ?></td>
                                    <td><?= $invoice['Customer_Id']; ?></td>
                                    <td><?= $invoice['c_phone_number']; ?></td>
                                    <td><?= $invoice['payment_mode']; ?></td>
                                    <td>
                                        <?php echo '$'; ?>
                                        <?=$invoice['Total']; ?>
                                    </td>
                                    <td><?= $invoice['Date']; ?></td>
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
                                    <td colspan="7"></td>
                                    <td class="align-middle"> 
                                        <strong>Total Revenue</strong>
                                    </td>
                                    <td class="align-middle">
                                        <?php 
                                            $total = 0;
                                            $calculateRevenue = mysqli_query($conn, "SELECT SUM(Total) AS total FROM sales");
                                            $total = mysqli_fetch_assoc($calculateRevenue);
                                            echo '$' , $total['total'];
                                        ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination Controls -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($current_page == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php if($current_page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if($current_page == $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php
            } else {
                echo '<h4>No invoice found.</h4>';
            }
        } else {
            header("Location: ../login/index.php");
        }
        ?>
    </div>
</body>
</html>
