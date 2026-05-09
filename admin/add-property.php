<?php

session_start();

require_once '../config/db.php';

/*
|--------------------------------------------------------------------------
| Authentication Check
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Initialize Variables
|--------------------------------------------------------------------------
*/
$error = "";
$success = "";

/*
|--------------------------------------------------------------------------
| Handle Form Submission
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize Inputs
    $title       = trim($_POST['title']);
    $price       = trim($_POST['price']);
    $location    = trim($_POST['location']);
    $description = trim($_POST['description']);

    /*
    |--------------------------------------------------------------------------
    | Validate Fields
    |--------------------------------------------------------------------------
    */
    if (
        empty($title) ||
        empty($price) ||
        empty($location) ||
        empty($description)
    ) {

        $error = "All fields are required.";

    } elseif (!is_numeric($price)) {

        $error = "Price must be numeric.";

    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {

        $error = "Please upload a property image.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Image Upload Handling
        |--------------------------------------------------------------------------
        */
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        $imageName = $_FILES['image']['name'];

        $imageTmp  = $_FILES['image']['tmp_name'];

        $imageSize = $_FILES['image']['size'];

        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Validate Extension
        if (!in_array($imageExt, $allowedExtensions)) {

            $error = "Only JPG, JPEG, PNG, and WEBP images are allowed.";

        }
        // Validate File Size (2MB)
        elseif ($imageSize > 2 * 1024 * 1024) {

            $error = "Image size must be less than 2MB.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Generate Unique Image Name
            |--------------------------------------------------------------------------
            */
            $newImageName = time() . '_' . uniqid() . '.' . $imageExt;

            $uploadPath = "../uploads/" . $newImageName;

            /*
            |--------------------------------------------------------------------------
            | Move Uploaded File
            |--------------------------------------------------------------------------
            */
            if (move_uploaded_file($imageTmp, $uploadPath)) {

                /*
                |--------------------------------------------------------------------------
                | Insert Property Into Database
                |--------------------------------------------------------------------------
                */
                $stmt = $conn->prepare(
                    "INSERT INTO properties
                    (title, price, location, description, image)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss",
                    $title,
                    $price,
                    $location,
                    $description,
                    $newImageName
                );

                if ($stmt->execute()) {

                    $success = "Property added successfully.";

                } else {

                    $error = "Database error occurred.";
                }

                $stmt->close();

            } else {

                $error = "Failed to upload image.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Property | Dream Properties</title>

    <link rel="stylesheet" href="../assets/css/add-property.style.css">

</head>

<body>

    <div class="page-container">

        <div class="form-card">

            <h1>Add New Property</h1>

            <p class="subtitle">
                Create a new property listing
            </p>

            <!-- Success Message -->
            <?php if (!empty($success)) : ?>

                <div class="success-message">
                    <?php echo $success; ?>
                </div>

            <?php endif; ?>

            <!-- Error Message -->
            <?php if (!empty($error)) : ?>

                <div class="error-message">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

            <!-- Form -->
            <form method="POST" enctype="multipart/form-data">

                <!-- Title -->
                <div class="form-group">

                    <label>Property Title</label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter property title"
                        required
                    >

                </div>

                <!-- Price -->
                <div class="form-group">

                    <label>Price</label>

                    <input
                        type="number"
                        name="price"
                        placeholder="Enter property price"
                        required
                    >

                </div>

                <!-- Location -->
                <div class="form-group">

                    <label>Location</label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Enter property location"
                        required
                    >

                </div>

                <!-- Description -->
                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Enter property description"
                        required
                    ></textarea>

                </div>

                <!-- Image -->
                <div class="form-group">

                    <label>Property Image</label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >

                </div>

                <!-- Button -->
                 <div class="button-group">

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>

    <button type="submit" class="submit-btn">
        Add Property
    </button>

</div>
               

            </form>

        </div>

    </div>

</body>

</html>