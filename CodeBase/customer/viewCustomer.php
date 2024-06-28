<?php
    require('../include/function.php');
    include('../include/databaseHandler.inc.php');

    // session_start();
    require_once('../include/config_session.inc.php');
    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
    include('../customer/header.html');

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
                
                $searchQuery = "";
                if (isset($_GET['search'])) {
                    $search = validate($_GET['search']);
                    $searchQuery = " WHERE CONCAT(Name, phone_number) LIKE '%$search%'";
                }
        
                $customers = getSearch('customers', $searchQuery);
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
                                        <td><?= $key + 1; ?></td>
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
    </div>
        
        <?php
            } else {
                echo '<h4>No customers found.</h4>';
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