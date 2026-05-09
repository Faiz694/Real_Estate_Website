<?php

session_start();

require_once '../config/db.php';

/*
|--------------------------------------------------------------------------
| Redirect If Already Logged In
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Initialize Variables
|--------------------------------------------------------------------------
*/
$error = "";

/*
|--------------------------------------------------------------------------
| Handle Login Request
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize Input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate Input
    if (empty($username) || empty($password)) {

        $error = "All fields are required.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Prepared Statement (SQL Injection Protection)
        |--------------------------------------------------------------------------
        */
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        /*
        |--------------------------------------------------------------------------
        | Verify User
        |--------------------------------------------------------------------------
        */
        if ($result->num_rows === 1) {

            $admin = $result->fetch_assoc();

            /*
            |--------------------------------------------------------------------------
            | Password Verification
            |--------------------------------------------------------------------------
            | Use password_hash() while storing passwords
            | Avoid md5() in production
            */
            if (password_verify($password, $admin['password'])) {

                // Store Session
                $_SESSION['admin'] = $admin['username'];

                // Redirect
                header("Location: dashboard.php");
                exit();

            } else {

                $error = "Invalid username or password.";
            }

        } else {

            $error = "Invalid username or password.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Dream Properties</title>

    <link rel="stylesheet" href="../assets/css/admin.style.css">

</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <h1>Admin Login</h1>

            <p class="login-subtitle">
                Access the Dream Properties admin dashboard
            </p>

            <!-- Error Message -->
            <?php if (!empty($error)) : ?>

                <div class="error-message">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="">

                <div class="form-group">

                    <label>Username</label>

                    <input
                        type="text"
                        name="username"
                        placeholder="Enter username"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >

                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>

</html>