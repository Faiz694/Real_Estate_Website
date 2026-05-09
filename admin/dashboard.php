<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

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
| Fetch Properties
|--------------------------------------------------------------------------
*/
$query = "SELECT * FROM properties ORDER BY id DESC";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Dream Properties</title>

    <link rel="stylesheet" href="../assets/css/admin-dashboard.style.css">

</head>

<body>

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <div class="sidebar-logo">
            <h2>Dream Properties</h2>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="active">
                Dashboard
            </a>

            <a href="add-property.php">
                Add Property
            </a>

            <a href="enquiries.php">
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
                <h1>Admin Dashboard</h1>
                <p>
                    Welcome,
                    <strong>
                        <?php echo htmlspecialchars($_SESSION['admin']); ?>
                    </strong>
                </p>
            </div>

        </header>

        <!-- Dashboard Stats -->
        <section class="stats-grid">

            <div class="stat-card">

                <h3>Total Properties</h3>

                <p>
                    <?php echo $result->num_rows; ?>
                </p>

            </div>

            <div class="stat-card">

                <h3>Active Listings</h3>

                <p>
                    <?php echo $result->num_rows; ?>
                </p>

            </div>

        </section>

        <!-- Property Table -->
        <section class="table-section">

            <div class="table-header">

                <h2>Property Listings</h2>

                <a href="add-property.php" class="add-btn">
                    + Add Property
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Location</th>
                            <th>Actions</th>

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

                                        <img
                                            src="../uploads/<?php echo htmlspecialchars($row['image']); ?>"
                                            alt="Property Image"
                                            class="property-image"
                                        >

                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </td>

                                    <td>
                                        ₹<?php echo number_format($row['price']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['location']); ?>
                                    </td>

                                    <td class="action-buttons">

                                        <a
                                            href="edit-property.php?id=<?php echo $row['id']; ?>"
                                            class="edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="delete-property.php?id=<?php echo $row['id']; ?>"
                                            class="delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this property?')"
                                        >
                                            Delete
                                        </a>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else : ?>

                            <tr>

                                <td colspan="6" class="empty-message">
                                    No properties found.
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