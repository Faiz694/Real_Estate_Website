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
| Fetch Property Data
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare(
    "SELECT image FROM properties WHERE id = ? LIMIT 1"
);

$stmt->bind_param("i", $propertyId);

$stmt->execute();

$result = $stmt->get_result();

/*
|--------------------------------------------------------------------------
| Check Property Exists
|--------------------------------------------------------------------------
*/
if ($result->num_rows !== 1) {

    $stmt->close();

    header("Location: dashboard.php");
    exit();
}

$property = $result->fetch_assoc();

$stmt->close();

/*
|--------------------------------------------------------------------------
| Delete Property Image
|--------------------------------------------------------------------------
*/
$imagePath = "../uploads/" . $property['image'];

if (
    !empty($property['image']) &&
    file_exists($imagePath)
) {

    unlink($imagePath);
}

/*
|--------------------------------------------------------------------------
| Delete Property From Database
|--------------------------------------------------------------------------
*/
$deleteStmt = $conn->prepare(
    "DELETE FROM properties WHERE id = ?"
);

$deleteStmt->bind_param("i", $propertyId);

$deleteStmt->execute();

$deleteStmt->close();

/*
|--------------------------------------------------------------------------
| Redirect Back
|--------------------------------------------------------------------------
*/
header("Location: dashboard.php");
exit();

?>