<?php
    require('../include/function.php');
    require('../include/databaseHandler.inc.php');
    require_once('../include/config_session.inc.php');

    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
        include('../supplier/header.html');
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Hello <?php echo $_SESSION['user_username']?></h2>
                <span>Suppliers</span>
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

        <div class="supplier-items-container">
            <ul class="supplier-menu">
                <li class="supplier-item">
                    <a href="../supplier/addSupplier.php">
                        <span>Add Supplier</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_username'])) { ?>
                <li class="supplier-item active">
                    <span>View Supplier</span>
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
                    $searchQuery = " WHERE CONCAT(Name, phone_number, Email) LIKE '%$search%'";
                }

                $suppliers = getSearch('suppliers', $searchQuery);
                if (!$suppliers) {
                    echo '<h4>Something went wrong.</h4>';
                    return false;
                }

                if (mysqli_num_rows($suppliers) > 0) {
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
                                        <th style="width: 150px;">Phone Number</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($suppliers as $key => $supplier) : ?>
                                    <tr class="align-middle">
                                        <td><?= $key + 1; ?></td>
                                        <td><?= $supplier['Name']; ?></td>
                                        <td><?= $supplier['phone_number']; ?></td>
                                        <td><?= $supplier['Email']; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="../supplier/editSupplier.php?Id=<?= $supplier['Id']; ?>" class="btn btn-primary">Edit</a>
                                                <a href="../supplier/deleteSupplier.php?Id=<?= $supplier['Id']; ?>" class="btn btn-danger">Remove</a>
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
            <?php
                } else {
                    echo '<h4>No suppliers found.</h4>';
                }

                include('../include/footer.html');
            ?>
        </div>
    </div>
</body>
</html>
<?php
} else {
    header("Location: ../login/index.php");
}
?>
