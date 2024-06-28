<?php
// Check if user gets here properly
if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $Username = $_POST['Username'];
    $Pwd = $_POST['Pwd'];

    echo "Username: $Username, Password: $Pwd";


    try {
        // Include necessary files
        require_once 'databaseHandler.inc.php';
        require_once '../include/config_session.inc.php';
        require_once '../include/signup_view.inc.php';
        require_once 'login_model.inc.php';
        require_once 'login_control.inc.php';

        // Initialize error array
        $errors = [];

        // Error handler for empty input
        if (is_input_empty($Username, $Pwd)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        // Get user data from the database
        $result = get_user($pdo, $Username);

        // Error handler for incorrect username
        if (is_username_wrong($result)) {
            $errors["username_incorrect"] = "Incorrect username!";
        }

        // Error handler for incorrect password
        if (!is_username_wrong($result) && is_password_wrong($Pwd, $result["Pwd"])) {
            $errors["password_incorrect"] = "Incorrect password!";
        }

        require_once 'config_session.inc.php';

        // Update session ID
        if ($errors) {
            $_SESSION["errors_login"] = $errors;

            // Preserve entered data except password
            $login_data = [
                "Username" => $Username,
            ];
            $_SESSION["login_data"] = $login_data;

            // Send user back to login page
            header("Location: ../login/index.php");
            die();
        }
        
        // Redirect user to the home page
        echo "Login successful!";
        header("Location: ../dashboard/index.php");
        // Start a session
        $newSessionId = session_create_id();
        $SessionId = $newSessionId."-".$result["Id"];
        session_id($SessionId);

        $_SESSION["user_id"] = $result["Id"];
        $_SESSION["user_username"] = htmlspecialchars($result["Username"]);
        $_SESSION["user_role"] = htmlspecialchars($result["Role"]);
        $_SESSION["last_regeneration"] = time();

        $pdo = null;
        $stmt = null;

        

    } catch (PDOException $e) {
        // Log the exception message and terminate script
        error_log($e->getMessage());
        die('Database error.');
    }

} else {
    // Handle the case where the user didn't submit the form
    header("Location: ../login/index.php");
    die();
}
