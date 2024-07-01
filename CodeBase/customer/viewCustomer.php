<?php
    require('../include/function.php');
    include('../include/databaseHandler.inc.php');

    // session_start();
    require_once('../include/config_session.inc.php');
    if (isset($_SESSION['user_username'])) {
    include('../customer/header.php');

?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Header -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Customers</span>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <form action="" method="GET">
                        <i class='bx bx-search'></i>
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                    </form>
                </div>
                <img src="../image/img.png" alt="user-pic">
            </div>
        </div>
        

        <!-- Title -->
        <div class="supplier-items-container">
            <ul class="supplier-menu">
                <li class="supplier-item">
                    <a href="../customer/addCustomer.php">
                        <span>Add Customers</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>View Customers</span>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="sp--wrapper">
            <?php 
                alertMessage(); 

                // Pagination settings
                $results_per_page = 20;
                $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($current_page - 1) * $results_per_page;

                // Search query
                $searchQuery = "";
                if (isset($_GET['search'])) {
                    $search = validate($_GET['search']);
                    $searchQuery = " WHERE CONCAT(Name, phone_number) LIKE '%$search%'";
                }

                // Get total number of customers
                $total_customers_query = "SELECT COUNT(*) as total FROM customers $searchQuery";
                $total_customers_result = mysqli_query($conn, $total_customers_query);
                $total_customers_row = mysqli_fetch_assoc($total_customers_result);
                $total_customers = $total_customers_row['total'];
                $total_pages = ceil($total_customers / $results_per_page);

                // Get customers with pagination
                $customers_query = "SELECT * FROM customers $searchQuery LIMIT $results_per_page OFFSET $offset";
                $customers = mysqli_query($conn, $customers_query);

                if (!$customers) {
                    echo '<h4>Something went wrong.</h4>';
                    return false;
                }

                if(mysqli_num_rows($customers) > 0){
            ?>
            <div class="row">
                <div class="col-12 mb-3 mb-lg-5">
                    <div class="position-relative card table-nowrap table-card">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($customers as $key => $customer) :?>
                                    <tr class="align-middle">
                                        <td><?= $offset + $key + 1; ?></td>
                                        <td><?= $customer['Name']; ?></td>
                                        <td><?= $customer['phone_number']; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="../customer/editCustomer.php?Id=<?= $customer['Id']; ?>" class="btn btn-primary">Edit</a>
                                                <a href="../customer/deleteCustomer.php?Id=<?= $customer['Id']; ?>" class="btn btn-danger">Remove</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
        } else {
            echo '<h4>No customers found.</h4>';
        }

        // Pagination controls
        if ($total_pages > 1) {
            $query_string = "?";
            if (isset($_GET['search'])) {
                $query_string .= "search=" . urlencode($_GET['search']) . "&";
            }
            
            echo '<nav aria-label="Page navigation">';
            echo '<ul class="pagination justify-content-center">';
            
            // First and Previous links
            if ($current_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="' . $query_string . 'page=1">First</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . $query_string . 'page=' . ($current_page - 1) . '">&lt; Prev</a></li>';
            }

            // Page number links
            $start_page = max(1, $current_page - 3);
            $end_page = min($total_pages, $current_page + 3);

            for ($page = $start_page; $page <= $end_page; $page++) {
                if ($page == $current_page) {
                    echo '<li class="page-item active"><a class="page-link" href="' . $query_string . 'page=' . $page . '">' . $page . '</a></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="' . $query_string . 'page=' . $page . '">' . $page . '</a></li>';
                }
            }

            // Next and Last links
            if ($current_page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="' . $query_string . 'page=' . ($current_page + 1) . '">Next &gt;</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . $query_string . 'page=' . $total_pages . '">Last</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
        }

        include('../include/footer.html');
    ?>

</body>
</html>
<?php
    } else {
        header("Location: ../login/index.php");
    }
?>
