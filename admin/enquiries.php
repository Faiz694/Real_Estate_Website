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
| Fetch Enquiries
|--------------------------------------------------------------------------
*/
$query = "SELECT * FROM enquiries ORDER BY id DESC";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Enquiries | Dream Properties</title>

    <link rel="stylesheet" href="../assets/css/enquiries.style.css">

</head>

<body>

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <div class="sidebar-logo">
            <h2>Dream Properties</h2>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="add-property.php">
                Add Property
            </a>

            <a href="enquiries.php" class="active">
                Enquiries
            </a>

            <a href="logout.php" class="logout-btn">
                Logout
            </a>

        </nav>

    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">

            <div>
                <h1>Customer Enquiries</h1>

                <p>
                    Manage and review customer messages
                </p>
            </div>

        </header>

        <!-- Table Section -->
        <section class="table-section">

            <div class="table-header">

                <h2>All Enquiries</h2>

                <a href="dashboard.php" class="back-btn">
                    ← Back to Dashboard
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Message</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($result->num_rows > 0) : ?>

                            <?php while ($row = $result->fetch_assoc()) : ?>

                                <tr>

                                    <td>
                                        <?php echo $row['id']; ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['phone']); ?>
                                    </td>

                                    <td class="message-cell">
                                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else : ?>

                            <tr>

                                <td colspan="4" class="empty-message">
                                    No enquiries found.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</body>

</html>