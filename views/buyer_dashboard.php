<?php
session_start();
require '../php/db.php'; // Ensure the database connection is included

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

$message = null;
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']); // Decode the error message
}


if (isset($_GET['wishlist_id'])) {
    $wishlist_id = $_GET['wishlist_id'];

    // Check if the property is already in the wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND property_id = ?");
    $stmt->bind_param("ii", $user_id, $wishlist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $wishlist_id);
        if ($stmt->execute()) {
            $message = "Property added to wishlist!";
        } else {
            $message = "Failed to add to wishlist: " . $stmt->error;
        }
    } else {
        $message = "Property already in wishlist!";
    }
}


// Search functionality
$search = $_GET['search'] ?? '';
$stmt = $conn->prepare("SELECT * FROM properties WHERE status='Unsold' AND location  LIKE? ");
$search_term = "%$search%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM wishlist WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$count_result = $stmt->get_result();
$count_row = $count_result->fetch_assoc();
$wishlist_count = $count_row['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buyer Dashboard - PropertY-Hub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script  type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        /* Navbar Styling */
.navbar {
    background: rgba(0, 0, 0, 0.9); /* Dark background */
    padding: 15px 20px;
    border-bottom: 2px solid gold; /* Stylish border at the bottom */
}

.navbar .navbar-brand {
    font-size: 1.8rem;
    color: gold; /* Luxury-themed text color */
    font-family: 'Georgia', serif;
    font-weight: bold;
}

.navbar .navbar-brand:hover {
    color: #d4af37; /* Slightly darker gold on hover */
}

.navbar .btn {
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    padding: 10px 20px;
    transition: all 0.3s;
}

.navbar .btn-primary {
    background-color: gold;
    color: black;
    border: none;
}

.navbar .btn-primary:hover {
    background-color: #d4af37; /* Darker gold on hover */
    color: white;
}

.navbar .btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}

.navbar .btn-danger:hover {
    background-color: #b02a37; /* Darker red on hover */
}

.badge {
    font-size: 0.9rem;
    vertical-align: middle;
}
        .container {
            margin-top: 50px;
        }
        .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}
.modal-dialog {
    height: auto; /* Ensure modal height is determined by its content */
    max-height: 90vh; /* Limit modal height to 90% of the viewport */
    display: flex;
    flex-direction: column;
}

.modal-content {
    height: 100%; /* Ensure the content fills the modal dialog */
    display: flex;
    flex-direction: column;
}

.modal-header,
.modal-footer {
    flex-shrink: 0; /* Ensure the header and footer are visible and don't shrink */
}

.modal-body {
    overflow-y: auto; /* Enable vertical scrolling for the body */
    max-height: calc(90vh - 120px); /* Subtract header and footer height */
    padding: 20px; /* Optional: Adjust padding for better appearance */
}

.card-img-top {
    height: 200px;
    object-fit: cover; /* Ensure images are cropped nicely */
    border-bottom: 1px solid #ddd;
}

.card-title {
    font-size: 1.25rem;
    color: #333;
}

.card-text {
    font-size: 1rem;
    color: #555;
}

.btn-primary {
    background-color: gold;
    color: black;
    border: none;
    transition: all 0.3s;
}

.btn-primary:hover {
    background-color: #d4af37;
    color: white;
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">PropertY-Hub</a>
        <div class="ms-auto d-flex align-items-center">
            <a href="wishlist.php" class="btn btn-primary me-2">
                Wishlist <span class="badge bg-danger"><?= $wishlist_count ?></span>
            </a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>


    <!-- Main Content -->
    <div class="container">
        <h2>Welcome, Buyer!</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form action="buyer_dashboard.php" method="GET" class="mt-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search properties by location" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

    <div class="row mt-3">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="Property Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                            <p class="card-text">Price: $<?= htmlspecialchars($row['price']) ?></p>
                            <p class="card-text">Description: <?= htmlspecialchars($row['description']) ?></p>
                            <button class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#propertyDetailsModal<?= $row['id'] ?>">View More</button>
                            <a href="wishlist.php?add_id=<?= $row['id'] ?>" class="btn btn-primary">Add to Wishlist</a>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="propertyDetailsModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">

                                            <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Property Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" class="img-fluid mb-3" alt="Property Image">
                                                    <div style="
                                                    display: flex;
                                                    justify-content: space-around;
                                                ">
                                                    <div style="color: black">
                                                    <p><strong>Title:</strong> <?= htmlspecialchars($row['title']) ?></p>
                                                    <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                                                    <p><strong>Price:</strong> $<?= htmlspecialchars($row['price']) ?></p>
                                                    <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                                                    <p><strong>Age:</strong> <?= htmlspecialchars($row['age']) ?> years</p>
                                                    <p><strong>Floor Plan:</strong> <?= htmlspecialchars($row['floor_plan']) ?> sq ft</p>
                                                    <p><strong>Bedrooms:</strong> <?= htmlspecialchars($row['bedrooms']) ?></p>
                                                    </div>
                                                    <div style="color: black">
                                                    <p><strong>Bathrooms:</strong> <?= htmlspecialchars($row['bathrooms']) ?></p>
                                                    <p><strong>Garden:</strong> <?= htmlspecialchars($row['garden']) === 'yes' ? 'Yes' : 'No' ?></p>
                                                    <p><strong>Parking:</strong> <?= htmlspecialchars($row['parking']) === 'yes' ? 'Yes' : 'No' ?></p>
                                                    <p><strong>Proximity to Facilities:</strong> <?= htmlspecialchars($row['proximity_facilities']) ?></p>
                                                    <p><strong>Proximity to Roads:</strong> <?= htmlspecialchars($row['proximity_roads']) ?></p>
                                                    <p><strong>Property Tax:</strong> $<?= htmlspecialchars($row['property_tax']) ?></p>
                                                    </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                </div>

                            </div>
            <?php endwhile; ?>
    </div>

    </div>
</body>
</html>
