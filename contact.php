<?php

require_once 'config/db.php';

/*
|--------------------------------------------------------------------------
| Default Variables
|--------------------------------------------------------------------------
*/

$name    = "";
$phone   = "";
$message = "";

$success = "";
$error   = "";

/*
|--------------------------------------------------------------------------
| Handle Form Submission
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | Sanitize Inputs
    |--------------------------------------------------------------------------
    */

    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (empty($name) || empty($phone)) {

        $error = "Name and phone number are required.";

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = "Enter a valid 10-digit phone number.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Insert Enquiry Securely
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare("
            INSERT INTO enquiries
            (name, phone, message)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "sss",
            $name,
            $phone,
            $message
        );

        if ($stmt->execute()) {

            $success = "Enquiry submitted successfully.";

            /*
            |--------------------------------------------------------------------------
            | Reset Fields
            |--------------------------------------------------------------------------
            */

            $name = "";
            $phone = "";
            $message = "";

        } else {

            $error = "Failed to submit enquiry.";
        }

        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us</title>

    <style>

        /* =========================
           Reset
        ========================= */

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        /* =========================
           Body
        ========================= */

        body{
            font-family:Arial, Helvetica, sans-serif;

            background:#f4f6f9;

            min-height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;

            padding:20px;
        }

        /* =========================
           Container
        ========================= */

        .container{
            width:100%;
            max-width:600px;

            background:white;

            padding:35px;

            border-radius:18px;

            box-shadow:0 6px 20px rgba(0,0,0,0.08);
        }

        /* =========================
           Heading
        ========================= */

        h1{
            text-align:center;

            margin-bottom:30px;

            color:#111827;

            font-size:2.2rem;
        }

        /* =========================
           Alerts
        ========================= */

        .alert{
            padding:14px 16px;

            border-radius:10px;

            margin-bottom:20px;

            font-size:0.95rem;
        }

        .success{
            background:#dcfce7;
            color:#166534;
        }

        .error{
            background:#fee2e2;
            color:#991b1b;
        }

        /* =========================
           Form
        ========================= */

        form{
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        input,
        textarea{

            width:100%;

            padding:14px;

            border:1px solid #d1d5db;

            border-radius:10px;

            font-size:1rem;

            outline:none;

            transition:0.2s ease;
        }

        input:focus,
        textarea:focus{
            border-color:#2563eb;
        }

        textarea{
            resize:vertical;
            min-height:150px;
        }

        /* =========================
           Button
        ========================= */

        button{

            padding:15px;

            border:none;

            border-radius:10px;

            background:#2563eb;

            color:white;

            font-size:1rem;
            font-weight:600;

            cursor:pointer;

            transition:0.3s ease;
        }

        button:hover{
            background:#1d4ed8;
        }

        /* =========================
           Mobile
        ========================= */

        @media(max-width:768px){

            .container{
                padding:25px;
            }

            h1{
                font-size:1.8rem;
            }

            input,
            textarea,
            button{
                font-size:1rem;
            }
        }

    </style>

</head>

<body>

    <div class="container">

        <h1>Contact Us</h1>

        <!-- Success Message -->
        <?php if (!empty($success)): ?>

            <div class="alert success">

                <?php echo htmlspecialchars($success); ?>

            </div>

        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>

            <div class="alert error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>

        <!-- Contact Form -->
        <form method="POST">

            <input
                type="text"
                name="name"
                placeholder="Enter Your Name"
                required
                value="<?php echo htmlspecialchars($name); ?>"
            >

            <input
                type="text"
                name="phone"
                placeholder="Enter Phone Number"
                required
                value="<?php echo htmlspecialchars($phone); ?>"
            >

            <textarea
                name="message"
                placeholder="Write your message..."
            ><?php echo htmlspecialchars($message); ?></textarea>

            <button type="submit">
                Send Enquiry
            </button>

        </form>

    </div>

</body>
</html>