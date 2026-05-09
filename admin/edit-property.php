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
| Validate Property ID
|--------------------------------------------------------------------------
*/
if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {
    header("Location: dashboard.php");
    exit();
}

$propertyId = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Fetch Property
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare(
    "SELECT * FROM properties WHERE id = ? LIMIT 1"
);

$stmt->bind_param("i", $propertyId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    header("Location: dashboard.php");
    exit();
}

$property = $result->fetch_assoc();

$stmt->close();

/*
|--------------------------------------------------------------------------
| Initialize Variables
|--------------------------------------------------------------------------
*/
$error = "";
$success = "";

/*
|--------------------------------------------------------------------------
| Handle Update Request
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize Inputs
    $title       = trim($_POST['title']);
    $price       = trim($_POST['price']);
    $location    = trim($_POST['location']);
    $type        = trim($_POST['type']);
    $description = trim($_POST['description']);

    /*
    |--------------------------------------------------------------------------
    | Validate Inputs
    |--------------------------------------------------------------------------
    */
    if (
        empty($title) ||
        empty($price) ||
        empty($location) ||
        empty($type) ||
        empty($description)
    ) {

        $error = "All fields are required.";

    } elseif (!is_numeric($price)) {

        $error = "Price must be numeric.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Default Existing Image
        |--------------------------------------------------------------------------
        */
        $imageName = $property['image'];

        /*
        |--------------------------------------------------------------------------
        | Handle New Image Upload
        |--------------------------------------------------------------------------
        */
        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            $fileName = $_FILES['image']['name'];

            $fileTmp  = $_FILES['image']['tmp_name'];

            $fileSize = $_FILES['image']['size'];

            $fileExt = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );

            // Validate Extension
            if (!in_array($fileExt, $allowedExtensions)) {

                $error = "Only JPG, JPEG, PNG and WEBP files allowed.";

            }
            // Validate File Size
            elseif ($fileSize > 2 * 1024 * 1024) {

                $error = "Image size must be less than 2MB.";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Generate Unique Image Name
                |--------------------------------------------------------------------------
                */
                $imageName = time() . '_' . uniqid() . '.' . $fileExt;

                $uploadPath = "../uploads/" . $imageName;

                /*
                |--------------------------------------------------------------------------
                | Upload Image
                |--------------------------------------------------------------------------
                */
                if (move_uploaded_file($fileTmp, $uploadPath)) {

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Old Image
                    |--------------------------------------------------------------------------
                    */
                    $oldImage = "../uploads/" . $property['image'];

                    if (
                        !empty($property['image']) &&
                        file_exists($oldImage)
                    ) {
                        unlink($oldImage);
                    }

                } else {

                    $error = "Failed to upload image.";
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Database
        |--------------------------------------------------------------------------
        */
        if (empty($error)) {

            $updateStmt = $conn->prepare(
                "UPDATE properties SET
                    title = ?,
                    price = ?,
                    location = ?,
                    type = ?,
                    description = ?,
                    image = ?
                 WHERE id = ?"
            );

            $updateStmt->bind_param(
                "ssssssi",
                $title,
                $price,
                $location,
                $type,
                $description,
                $imageName,
                $propertyId
            );

            if ($updateStmt->execute()) {

                header("Location: dashboard.php");
                exit();

            } else {

                $error = "Failed to update property.";
            }

            $updateStmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Property | Dream Properties</title>

    <link rel="stylesheet" href="../assets/css/edit-property.style.css">

</head>

<body>

    <div class="page-container">

        <div class="form-card">

            <h1>Edit Property</h1>

            <p class="subtitle">
                Update property details
            </p>

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
                        value="<?php echo htmlspecialchars($property['title']); ?>"
                        required
                    >

                </div>

                <!-- Price -->
                <div class="form-group">

                    <label>Price</label>

                    <input
                        type="number"
                        name="price"
                        value="<?php echo htmlspecialchars($property['price']); ?>"
                        required
                    >

                </div>

                <!-- Location -->
                <div class="form-group">

                    <label>Location</label>

                    <input
                        type="text"
                        name="location"
                        value="<?php echo htmlspecialchars($property['location']); ?>"
                        required
                    >

                </div>

                <!-- Type -->
                <div class="form-group">

                    <label>Property Type</label>

                    <input
                        type="text"
                        name="type"
                        value="<?php echo htmlspecialchars($property['type']); ?>"
                        required
                    >

                </div>

                <!-- Description -->
                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                    ><?php echo htmlspecialchars($property['description']); ?></textarea>

                </div>

                <!-- Current Image -->
                <div class="form-group">

                    <label>Current Image</label>

                    <img
                        src="../uploads/<?php echo htmlspecialchars($property['image']); ?>"
                        alt="Property Image"
                        class="property-image"
                    >

                </div>

                <!-- Upload New Image -->
                <div class="form-group">

                    <label>Upload New Image</label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    Update Property
                </button>

            </form>

        </div>

    </div>

</body>

</html>