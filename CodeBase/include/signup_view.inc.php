<?php
    //Allowing type declaration
    declare(strict_types=1);

    function signup_inputs(){       //not yet working
        // Check if a certain session variable existing inside the sign up page
        // Check if the entered ID was valid
        echo '  <link rel="stylesheet" href="style.css">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';

        // Check if the entered username was valid
        if (isset($_SESSION["signup_data"]["Username"]) && !isset($_SESSION["errors_signup"]["username_taken"])) {
            echo '<div class="input-box">
                    <span class="icon"><i class="bx bx-user" ></i></span>
                    <input name ="Username" type="text" required id="Username" value = "' . $_SESSION["signup_data"]["Username"] . '">
                    <label>Username</label>
                    <p>Invalid Username</p>
                </div>';
        } else {   
            echo '<div class="input-box">
                    <span class="icon"><i class="bx bx-user" ></i></span>
                    <input name ="Username" type="text" required id="Username" >
                    <label>Username</label>
                    <p>Invalid Username</p>
                </div>';
        }

        // // Full name
        // echo '<div class="input-box">
        //         <span class="icon"><i class="bx bx-user" ></i></span>
        //         <input name ="FullName" type="text" required id="FullName" value ="' . $_SESSION["signup_data"]["FullName"] . '">
        //         <label>Full name</label>
        //         <p>Invalid Name</p>
        //     </div>';
        
        // Password
        echo '<div class="input-box">
                <span class="icon"><i class="bx bx-lock" ></i></span>
                <input name ="Pwd" type="password" required id="Pwd">
                <label>Password</label>
                <p>Invalid Password</p>
            </div>';

        // Check if the entered email was valid and not used
        if (isset($_SESSION["signup_data"]["Email"]) && !isset($_SESSION["errors_signup"]["invalid_email"])
            && !isset($_SESSION["errors_signup"]["email_used"]) ) {
            echo '<div class="input-box">
                    <span class="icon"><i class="bx bx-user" ></i></span>
                    <input name = "Email" type="text" required id="Email" value = "' . $_SESSION["signup_data"]["Email"] . '">
                    <label>Email</label>
                    <p>Invalid Email</p>
                </div>';
            // '<input type = "text" name = "Username" placeholder = "Username" value = " ' . $_SESSION["signup_data"]["Username"] . ' ">';
        } else {   
            echo '<div class="input-box">
            <span class="icon"><i class="bx bx-user" ></i></span>
            <input name = "Email" type="text" required id="Email">
            <label>Email</label>
            <p>Invalid Email</p>
        </div>';
        }

        //bootstrap
        echo '<script src="script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>';    
    
    }

    function check_signup_errors(){
        if (isset($_SESSION['errors_signup'])){
            $errors = $_SESSION['errors_signup'];

            echo "<br>";

            foreach ($errors as $error){
                echo '<p class="alert alert-danger">' . $error . '</p>';
            }

            //delete session variable 
            unset($_SESSION['errors_signup']);
        } else if(isset($_GET["signup"]) && $_GET["signup"] === "success"){
            echo '<p class="alert alert-success"> Sign up successfully </p> ';
        }
    }