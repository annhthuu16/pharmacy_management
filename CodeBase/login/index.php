<?php
    // Start a session in order to grab error messages
    require_once '../include/config_session.inc.php';
    require_once '../include/signup_view.inc.php';
    require_once '../include/login_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management System - Appotheke</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="center">
        <div class="form-box login">
            <form action="../include/login.inc.php" method="post">
                <div class="top">
                    <div class="logo"></div>
        
                    <!-- <label>
                        <a href="#">Create Account</a>
                    </label> -->
                </div>
                <h2>Log In</h2>
                <h4>Enter Your Account Details</h3>
                    
                <div class="input-box">
                    <span class="icon"><i class='bx bx-user' ></i></span>
                    <input type="text" required name="Username" id="Username">
                    <label>Username</label>
                    <?php if(isset($_SESSION['error']) && $_SESSION['error'] == "Invalid Username"): ?>
                        <p>Invalid Username</p>
                    <?php endif; ?>
                </div>


                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock' ></i></span>
                    <input type="password" required name="Pwd" id="Pwd">
                    <label>Password</label>
                    <?php if(isset($_SESSION['error']) && $_SESSION['error'] == "Invalid Password"): ?>
                        <p>Invalid Password</p>
                    <?php endif; ?>
                </div>

                <?php
                    // Check if there are any errors
                    check_login_error();
                ?>
                <!-- 
                <div class="sign-up">
                    <label>
                        <a href="../signup/index.php">Sign up Here</a>
                    </label>
                </div>

                <!- <div class="remember-forgot"> -->
                    <!-- <label><input type="checkbox"> Remember me </label> -->

                    <!-- <label>
                        <a href="">Forgot Password?</a>
                    </label>
                </div> -->

                <input type="submit" class="btn btn-primary" name="submit" value="Login">

            </form>
            <!-- Login in form ends here -->
            
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>