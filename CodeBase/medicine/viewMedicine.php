<?php
require('../include/function.php');
include('../include/databaseHandler.inc.php');

// session_start();
require_once('../include/config_session.inc.php');
if (isset($_SESSION['user_username'])) {
    include('../medicine/header.php');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table td, .table th {
        white-space: normal !important;
        word-wrap: break-word;
        max-width: 200px; /* Adjust as needed for your layout */
    }
    .sp--wrapper {
        background-color: white !important;
    }
</style>

<!-- Header -->
<div class="container-fluid">
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Medicine</span>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <form id="searchForm" action="" method="GET">
                        <i class='bx bx-search'></i>
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                        <?php if (isset($_GET['filter'])) { ?>
                        <input type="hidden" name="filter" value="<?php echo htmlspecialchars($_GET['filter']); ?>">
                        <?php } ?>
                    </form>
                </div>
                <img src="../image/img.png" alt="user-pic">
            </div>
        </div>

        <!-- Title -->
        <div class="supplier-items-container">
            <ul class="supplier-menu">
                <?php if (isset($_SESSION['user_username']) && ($_SESSION['user_role'] == "Admin")) { ?>
                <li class="supplier-item">
                    <a href="../medicine/addMedicine.php">
                        <span>Add Medicine</span>
                    </a>
                </li>
                <?php } ?>

                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>View Medicine</span>
                </li>
                <?php } ?>
            </ul>
        </div>

        <!-- New Form for Select Box -->
        <form id="filterForm" method="GET" action="">
            <label for="filter">Select Medicine</label>
            <select name="filter" id="filter" onchange="document.getElementById('filterForm').submit();">
                <option value="all" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'all' ? 'selected' : ''; ?>>All</option>
                <option value="expired" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'expired' ? 'selected' : ''; ?>>Expired</option>
                <option value="available" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'available' ? 'selected' : ''; ?>>Available</option>
            </select>
            <?php if (isset($_GET['search'])) { ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
            <?php } ?>
        </form>

        <div class="sp--wrapper">
            <?php 
                alertMessage(); 

                $searchQuery = "";
                if (isset($_GET['search'])) {
                    $search = validate($_GET['search']);
                    $searchQuery = " AND CONCAT(medicine_name, Price, Type, Id_supplier, quantity, expiry_date) LIKE '%$search%'";
                }

                // Handle filter
                $filterQuery = "";
                if (isset($_GET['filter'])) {
                    $filter = $_GET['filter'];
                    $today = date('Y-m-d');
                    if ($filter == 'expired') {
                        $filterQuery = " AND expiry_date <= '$today'";
                    } elseif ($filter == 'available') {
                        $filterQuery = " AND expiry_date > '$today'";
                    }
                }

                // Pagination settings
                $results_per_page = 20;
                $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($current_page - 1) * $results_per_page;

                // Get total number of medicines
                $total_medicines_query = "SELECT COUNT(*) as total FROM medicines WHERE 1=1 $searchQuery $filterQuery";
                $total_medicines_result = mysqli_query($conn, $total_medicines_query);
                $total_medicines_row = mysqli_fetch_assoc($total_medicines_result);
                $total_medicines = $total_medicines_row['total'];
                $total_pages = ceil($total_medicines / $results_per_page);

                // Get medicines with pagination
                $query = "SELECT * FROM medicines WHERE 1=1 $searchQuery $filterQuery LIMIT $results_per_page OFFSET $offset";
                $medicines = mysqli_query($conn, $query);
                if (!$medicines) {
                    echo '<h4>Something went wrong.</h4>';
                    return false;
                }

                if (mysqli_num_rows($medicines) > 0) {
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
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th>Supplier ID</th>
                                        <th>Quantity</th>
                                        <th>Expiry Date</th>
                                        <?php if ($_SESSION['user_role'] == 'Admin') : ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicines as $key => $medicine) : ?>
                                    <tr class="align-middle">
                                        <td><?= $offset + $key + 1; ?></td>
                                        <td><?= $medicine['medicine_name']; ?></td>
                                        <td><?= $medicine['Price']; ?></td>
                                        <td><?= $medicine['Type']; ?></td>
                                        <td><?= $medicine['Id_supplier']; ?></td>
                                        <td><?= $medicine['quantity']; ?></td>
                                        <td><?= $medicine['expiry_date']; ?></td>
                                        <?php if ($_SESSION['user_role'] == 'Admin') : ?>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="../medicine/editMedicine.php?Id=<?= $medicine['Id']; ?>" class="btn btn-primary">Edit</a>
                                                <a href="../medicine/deleteMedicine.php?Id=<?= $medicine['Id']; ?>" class="btn btn-danger">Remove</a>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            } else {
                echo '<h4>No medicine found.</h4>';
            }

            // Pagination controls
            if ($total_pages > 1) {
                $query_string = "?";
                if (isset($_GET['search'])) {
                    $query_string .= "search=" . urlencode($_GET['search']) . "&";
                }
                if (isset($_GET['filter'])) {
                    $query_string .= "filter=" . urlencode($_GET['filter']) . "&";
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