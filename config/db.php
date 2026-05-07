<?php
// Enable strict MySQLi error reporting (development)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli("localhost", "root", "", "realestate");

    // Set charset (important for security + encoding)
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    // Log actual error (do NOT expose to users)
    error_log("DB Connection Error: " . $e->getMessage());

    // Generic message for users
    die("Database connection failed. Please try again later.");
}
?>