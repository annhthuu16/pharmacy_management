<?php
    require('../include/function.php');
    include('../include/databaseHandler.inc.php');

    // session_start();
    require_once('../include/config_session.inc.php');
    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
    include('../medicine/header.html');
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
                        <a href="../medicine/addMedicine.php">
                            <span>Add Medicine</span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_username'])) { ?>
                    <li class="supplier-item active">
                        <span>View Medicine</span>
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
                        $searchQuery = " WHERE CONCAT(medicine_name, Price, Type, Id_supplier, quantity, expiry_date) LIKE '%$search%'";
                    }

                    $medicines = getSearch('medicines', $searchQuery);
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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($medicines as $key => $medicine) : ?>
                                        <tr class="align-middle">
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $medicine['medicine_name']; ?></td>
                                            <td><?= $medicine['Price']; ?></td>
                                            <td><?= $medicine['Type']; ?></td>
                                            <td><?= $medicine['Id_supplier']; ?></td>
                                            <td><?= $medicine['quantity']; ?></td>
                                            <td><?= $medicine['expiry_date']; ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="../medicine/editMedicine.php?Id=<?= $medicine['Id']; ?>" class="btn btn-primary">Edit</a>
                                                    <a href="../medicine/deleteMedicine.php?Id=<?= $medicine['Id']; ?>" class="btn btn-danger">Remove</a>
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
            echo '<h4>No medicine found.</h4>';
        }

        include('../include/footer.html');
        ?>

</body>
</html>
<?php
    }else {
        header("Location: ../login/index.php");
    }
?>