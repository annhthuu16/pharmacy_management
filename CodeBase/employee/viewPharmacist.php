<?php
    require('../include/function.php');
    include('../include/databaseHandler.inc.php');

    // session_start();
    require_once('../include/config_session.inc.php');
    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management System - Appotheke</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table td, .table th {
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 200px; /* Adjust as needed for your layout */
        }
    </style>
</head>

<body>
    <!-- Sidebar Start -->
    <div class="sidebar">
        <div class="logo">
            <img src="../image/Logo.png" alt="Logo">
            <span class="brand-name">APPOTHEKE</span>
        </div>
        
        <ul class="menu">
            <li><a href="../dashboard/index.php"><i class='bx bxs-dashboard'></i><span>Dashboard</span></a></li>
            
            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../signup/index.php"><i class='bx bx-user-plus'></i><span>Create Account</span></a></li>
            <?php }?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../sale/viewSale.php"><i class='bx bx-line-chart' ></i><span>Sale</span></a></li>
            <?php }?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
            <li><a href="../supplier/addSupplier.php"><i class='bx bx-package'></i><span>Suppliers</span></a></li>
            <?php } ?>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?> 
            <li><a href="../medicine/addMedicine.php"><i class='bx bxs-capsule' ></i><span>Inventory</span></a></li>
            <?php }
            else {?>
            <li><a href="../medicine/viewMedicine.php"><i class='bx bx-capsule' ></i><span>Inventory</span></a></li>
            <?php } ?> 

            <li><a href="../customer/addCustomer.php"><i class='bx bx-street-view'></i><span>Customers</span></a></li>
            
            <li><a href="../invoice/invoiceForm.php"><i class='bx bx-credit-card' ></i><span>Invoices</span></a></li>

            <li><a href="../newChat/chat.php"><i class='bx bx-conversation' ></i><span>Messages</span></a></li>

            <?php if ($_SESSION['user_role'] == 'Admin') { ?> 
            <li class="active"><a href="../employee/viewPharmacist.php"><i class='bx bx-group' ></i><span>Employee</span></a></li>

            <?php } ?>
            <li class="logout">
                <form action="../include/logout.inc.php" method="post">
                    <button type="submit" name="logout-submit" class="logout">
                        <a href="#">
                            <i class='bx bx-log-out bx-rotate-180' ></i>
                            <span>Logout</span>
                        </a>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <!-- Sidebar End -->

    <!-- Main Content Start -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Pharmacist</span>
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
                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>View Pharmacist</span>
                </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Pharmacist Table -->
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
                    $searchQuery = " WHERE CONCAT(Id, Username, FullName, Email, Role) LIKE '%$search%'";
                }

                // Get total number of pharmacists
                $total_pharmacists_query = "SELECT COUNT(*) as total FROM pharmacists $searchQuery";
                $total_pharmacists_result = mysqli_query($conn, $total_pharmacists_query);
                $total_pharmacists_row = mysqli_fetch_assoc($total_pharmacists_result);
                $total_pharmacists = $total_pharmacists_row['total'];
                $total_pages = ceil($total_pharmacists / $results_per_page);

                // Get pharmacists with pagination
                $pharmacists_query = "SELECT Id, Username, FullName, Email, Role FROM pharmacists $searchQuery LIMIT $results_per_page OFFSET $offset";
                $pharmacists = mysqli_query($conn, $pharmacists_query);

                if (!$pharmacists) {
                    echo '<h4>Something went wrong.</h4>';
                    return false;
                }

                if (mysqli_num_rows($pharmacists) > 0) {
            ?>
            <div class="row">
                <div class="col-12 mb-3 mb-lg-5">
                    <div class="position-relative card table-nowrap table-card">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pharmacists as $key => $pharmacist) : ?>
                                    <tr class="align-middle">
                                        <td><?= $offset + $key + 1; ?></td>
                                        <td><?= $pharmacist['Username']; ?></td>
                                        <td><?= $pharmacist['FullName']; ?></td>
                                        <td><?= $pharmacist['Email']; ?></td>
                                        <td><?= $pharmacist['Role']; ?></td>
                                        <td> 
                                            <div class="btn-group" role="group">
                                                <a href="../employee/editPharmacist.php?Id=<?= $pharmacist['Id']; ?>" class="btn btn-primary">Edit</a>
                                                <a href="../employee/deletePharmacist.php?Id=<?= $pharmacist['Id']; ?>" class="btn btn-danger">Remove</a>
                                            </div> 
                                        </td>
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
            echo '<h4>No pharmacists found.</h4>';
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
