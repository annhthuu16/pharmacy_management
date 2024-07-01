<?php
    // Start a session in order to grab error messages
    require_once '../include/config_session.inc.php';
    require_once '../include/signup_view.inc.php';

    if (isset($_SESSION['user_username']) && $_SESSION['user_role'] == 'Admin') {
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="center">
        <div class="form-box login">
            <!--Sign up form starts here-->
            <form class = "header" action="../include/signup.inc.php" method="post">
                <div class = "top">
                    <div class="logo"></div>
                </div>
                <h2>Create Account</h2>
                <h4>Enter Account Details</h4>
                   
                <!-- <div class="input-box">
                    <span class="icon"><i class="bx bx-id-card" ></i></span>
                    <input name = "Id" type="number" required id="Id">
                    <label>UserID</label>
                    <p>Invalid UserID</p>
                </div> -->

                <div class="input-box">
                    <span class="icon"><i class="bx bx-user" ></i></span>
                    <input name = "Username" type="text" required id="Username">
                    <label>Username</label>
                    <p>Invalid Username</p>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-user' ></i></span>
                    <input name = "FullName" type="text" required id="FullName">
                    <label>Full Name</label>
                    <p>Invalid Name</p>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-user' ></i></span>
                    <input name = "Email" type="text" required id="Email">
                    <label>Email address</label>
                    <p>Invalid email address</p>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-user' ></i></span>
                    <input name = "Role" type="text" required id="Role">
                    <label>Role</label>
                    <p>Invalid Role</p>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock' ></i></span>
                    <input name = "Pwd" type="password" required id="Pwd">
                    <label>Password</label>
                    <p>Invalid Password</p>
                </div>

                <div class="remember-forgot">
                    <!-- <label><input type="checkbox"> Remember me </label> -->

                    <label>
                        <a href="../dashboard/index.php">Back to dashboard</a>
                    </label>
                </div>
                
                
                <input type="submit" class="btn btn-primary" name="submit" value="Create Account">
                
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>

<?php
    //Show error/success message to user
    check_signup_errors();
    }
    else{
        header("Location: ../login/index.php");
    }
?>