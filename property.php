<?php
require_once 'config/db.php';

/*
|--------------------------------------------------------------------------
| Validate Property ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Property ID");
}

$id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Fetch Property Securely
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT 
        id,
        title,
        price,
        image,
        description,
        location,
        type
    FROM properties
    WHERE id = ?
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Property Not Found");
}

$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($row['title']); ?>
    </title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#f4f6f9;
            color:#222;
            line-height:1.6;
        }

        .container{
            max-width:1200px;
            margin:auto;
            padding:40px 20px;
        }

        /*
        ===================================
        Property Card
        ===================================
        */

        .property-details{
            background:white;

            border-radius:16px;

            overflow:hidden;

            box-shadow:0 6px 20px rgba(0,0,0,0.08);
        }

        /*
        ===================================
        Property Image
        ===================================
        */

        .property-image{
            width:100%;
            height:500px;
            object-fit:cover;
            display:block;
        }

        /*
        ===================================
        Content
        ===================================
        */

        .property-content{
            padding:35px;
        }

        h1{
            font-size:clamp(2rem, 4vw, 3.2rem);
            margin-bottom:20px;
            color:#111827;
        }

        .price{
            font-size:1.8rem;
            color:#16a34a;
            font-weight:bold;
            margin-bottom:20px;
        }

        .meta{
            display:flex;
            flex-wrap:wrap;
            gap:15px;

            margin-bottom:25px;
        }

        .meta span{
            background:#eef2ff;
            color:#1e3a8a;

            padding:10px 16px;

            border-radius:8px;

            font-size:0.95rem;
            font-weight:600;
        }

        .description{
            font-size:1.05rem;
            color:#444;
            margin-bottom:35px;
        }

        /*
        ===================================
        Action Buttons
        ===================================
        */

        .actions{
            display:flex;
            gap:15px;
            flex-wrap:wrap;
        }

        .btn{
            flex:1;

            text-align:center;

            text-decoration:none;

            padding:14px 20px;

            border-radius:10px;

            color:white;

            font-weight:600;

            transition:0.3s ease;
        }

        .whatsapp{
            background:#25d366;
        }

        .whatsapp:hover{
            background:#1ebe5d;
        }

        .call{
            background:#2563eb;
        }

        .call:hover{
            background:#1d4ed8;
        }

        /*
        ===================================
        Mobile
        ===================================
        */

        @media(max-width:768px){

            .container{
                padding:20px 15px;
            }

            .property-image{
                height:320px;
            }

            .property-content{
                padding:25px;
            }

            .price{
                font-size:1.5rem;
            }

            .description{
                font-size:1rem;
            }

            .actions{
                flex-direction:column;
            }
        }

    </style>

</head>

<body>

<div class="container">

    <div class="property-details">

        <!-- Property Image -->
        <img
            src="uploads/<?php echo htmlspecialchars($row['image']); ?>"
            alt="<?php echo htmlspecialchars($row['title']); ?>"
            class="property-image"
        >

        <!-- Content -->
        <div class="property-content">

            <h1>
                <?php echo htmlspecialchars($row['title']); ?>
            </h1>

            <div class="price">
                <?php echo htmlspecialchars($row['price']); ?>
            </div>

            <!-- Meta -->
            <div class="meta">

                <span>
                    📍 <?php echo htmlspecialchars($row['location']); ?>
                </span>

                <span>
                    🏠 <?php echo htmlspecialchars($row['type']); ?>
                </span>

            </div>

            <!-- Description -->
            <p class="description">

                <?php echo nl2br(htmlspecialchars($row['description'])); ?>

            </p>

            <!-- Actions -->
            <div class="actions">

                <a
                    href="https://wa.me/91XXXXXXXXXX"
                    class="btn whatsapp"
                    target="_blank"
                >
                    WhatsApp
                </a>

                <a
                    href="tel:XXXXXXXXXX"
                    class="btn call"
                >
                    Call Now
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>