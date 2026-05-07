<?php
require_once 'config/db.php';


// Secure Search Filters

$location = trim($_GET['location'] ?? '');
$type = trim($_GET['type'] ?? '');

//  Base Query

$sql = "
    SELECT id, title, price, location, type
    FROM properties
    WHERE 1=1
";

$params = [];
$types  = "";


//  Dynamic Filters

if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%{$location}%";
    $types .= "s";
}

if (!empty($type)) {
    $sql .= " AND type LIKE ?";
    $params[] = "%{$type}%";
    $types .= "s";
}

// Sorting
// |--------------------------------------------------------------------------

$sql .= " ORDER BY id DESC";


// Prepared Statement
// |--------------------------------------------------------------------------

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- Mobile Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Property Search</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            background:#f4f6f9;
            color:#222;
            padding:40px 20px;
        }

        .container{
            max-width:1200px;
            margin:auto;
        }

        h1{
            text-align:center;
            margin-bottom:35px;
            font-size:2.5rem;
        }

        
        /* Search Form */
        /* ================================== */
        .search-form{
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 4px 14px rgba(0,0,0,0.08);

            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:15px;

            margin-bottom:40px;
        }

        .search-form input{
            width:100%;
            padding:14px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:1rem;
            outline:none;
        }

        .search-form input:focus{
            border-color:#3498db;
        }

        .search-form button{
            background:#3498db;
            color:white;
            border:none;
            border-radius:8px;
            padding:14px;
            font-size:1rem;
            cursor:pointer;
            transition:0.3s ease;
        }

        .search-form button:hover{
            background:#2980b9;
        }

        /*
        ==================================
        Property Grid
        ==================================
        */

        .property-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            gap:25px;
        }

        /*
        ==================================
        Property Card
        ==================================
        */

        .card{
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 4px 14px rgba(0,0,0,0.08);

            transition:0.3s ease;
        }

        .card:hover{
            transform:translateY(-5px);
            box-shadow:0 10px 24px rgba(0,0,0,0.12);
        }

        .card h3{
            margin-bottom:12px;
            font-size:1.4rem;
            color:#111827;
        }

        .card p{
            margin-bottom:10px;
            color:#555;
            font-size:1rem;
        }

        .price{
            color:#16a34a !important;
            font-weight:bold;
            font-size:1.2rem !important;
        }

        .card a{
            display:inline-block;
            margin-top:15px;

            text-decoration:none;

            background:#3498db;
            color:white;

            padding:12px 18px;
            border-radius:8px;

            transition:0.3s ease;
        }

        .card a:hover{
            background:#2980b9;
        }

        /*
        ==================================
        Empty State
        ==================================
        */

        .empty{
            text-align:center;
            background:white;
            padding:40px;
            border-radius:12px;
            box-shadow:0 4px 14px rgba(0,0,0,0.08);
        }

        /*
        ==================================
        Mobile
        ==================================
        */

        @media(max-width:768px){

            body{
                padding:20px 15px;
            }

            h1{
                font-size:2rem;
            }

            .search-form{
                grid-template-columns:1fr;
            }

            .property-grid{
                grid-template-columns:1fr;
            }

            .card{
                padding:20px;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <h1>Search Properties</h1>

    <!-- Search Form -->
    <form method="GET" class="search-form">

        <input
            type="text"
            name="location"
            placeholder="Enter Location"
            value="<?php echo htmlspecialchars($location); ?>"
        >

        <input
            type="text"
            name="type"
            placeholder="Property Type"
            value="<?php echo htmlspecialchars($type); ?>"
        >

        <button type="submit">
            Search
        </button>

    </form>

    <!-- Results -->
    <div class="property-grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while($row = $result->fetch_assoc()): ?>

                <div class="card">

                    <h3>
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h3>

                    <p class="price">
                        <?php echo htmlspecialchars($row['price']); ?>
                    </p>

                    <p>
                        Location:
                        <?php echo htmlspecialchars($row['location']); ?>
                    </p>

                    <p>
                        Type:
                        <?php echo htmlspecialchars($row['type']); ?>
                    </p>

                    <a href="property.php?id=<?php echo $row['id']; ?>">
                        View Property
                    </a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty">
                <h2>No Properties Found</h2>
                <p>Try changing search filters.</p>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>