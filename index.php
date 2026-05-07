<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Real Estate</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>Dream Properties</h1>
</header>

<section class="hero">
    <h2>Find Your Dream Home</h2>
</section>
<section>
    <h2>Featured Properties</h2>
 
    <div id="cardContainer">
    <?php
    // 1. Check DB connection
    if (!$conn) {
        echo "<p>Database connection failed.</p>";
    } else {

        // 2. Run query safely
        $sql = "SELECT id, title, price, image FROM properties LIMIT 3";
        $result = $conn->query($sql);

        // 3. Check query execution
        if ($result === false) {
            error_log("SQL Error: " . $conn->error); // log internally
            echo "<p>Unable to load properties right now.</p>";
        } else {

            // 4. Check if data exists
            if ($result->num_rows === 0) {
                echo "<p>No properties available.</p>";
            } else {

                // 5. Render safely
                while ($row = $result->fetch_assoc()) {
                    $id = (int)$row['id'];
                    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                    $price = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                    $image = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');

                    echo "
                    <div class='card'>
                        <img src='uploads/$image' alt='property'>
                        <h3>$title</h3>
                        <p>$price</p>
                        <a href='property.php?id=$id'>View</a>
                    </div>";
                }
            }
        }
    }
    ?></div>
</section>


</body>
</html> 


