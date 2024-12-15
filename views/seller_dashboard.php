<?php
session_start();
require '../php/db.php'; // Include the database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is logged in and is a seller
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the image file path for the property
    $stmt = $conn->prepare("SELECT image_url FROM properties WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();

    // Check if an image exists and delete it
    if ($property && !empty($property['image_url'])) {
        $image_path = __DIR__ . '/../assets/images/'. $property['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete the property from the wishlist table
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE property_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Optional: Log or message about the wishlist deletion
    } else {
        $message = "Failed to delete property from wishlist: " . $stmt->error;
    }

    // Delete the property from the properties table
    $stmt = $conn->prepare("DELETE FROM properties WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        $message = "Property and associated image deleted successfully!";
    } else {
        $message = "Failed to delete property: " . $stmt->error;
    }
}



// Handle property addition
if (isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $age = $_POST['age'];
    $floor_plan = $_POST['floor_plan'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $garden = $_POST['garden'];
    $parking = $_POST['parking'];
    $proximity_facilities = $_POST['proximity_facilities'];
    $proximity_roads = $_POST['proximity_roads'];
    $property_tax = ($_POST['price'] * 0.07);

    // Handle image upload
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_dir = __DIR__ . '/../assets/images/';
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_name = $image_name;
        } else {
            $message = "Failed to upload image.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO properties (user_id, title, location, price, description, age, floor_plan, bedrooms, bathrooms, garden, parking, proximity_facilities, proximity_roads, property_tax, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debugging step
    }

    // Bind parameters
if (!$stmt->bind_param("issdsiiiiiissss", $_SESSION['user_id'], $title, $location, $price, $description, $age, $floor_plan, $bedrooms, $bathrooms, $garden, $parking, $proximity_facilities, $proximity_roads, $property_tax, $image_name)) {
    die("Bind failed: " . $stmt->error);
}

    
    if ($stmt->execute()) {
        $message = "Property added successfully!";
        header("Location: seller_dashboard.php?message=Property added successfully");
        exit();
    } else {
        $message = "Failed to add property: " . $stmt->error;
        header("Location: seller_dashboard.php?message="+$message);
        exit();
    }
}

// Handle property update
if (isset($_POST['update_property'])) {
    $update_id = $_POST['property_id'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $age = $_POST['age'];
    $floor_plan = $_POST['floor_plan'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];

    // Validate garden input
    $garden = isset($_POST['garden']) && ($_POST['garden'] === 'yes' || $_POST['garden'] === 'no') 
        ? $_POST['garden'] 
        : 'no';

    $parking = $_POST['parking'];
    $proximity_facilities = $_POST['proximity_facilities'];
    $proximity_roads = $_POST['proximity_roads'];
    $property_tax = ($_POST['price'] * 0.07);

    // Retrieve the current image file path
    $stmt = $conn->prepare("SELECT image_url FROM properties WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $update_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentImage = $result->fetch_assoc()['image_url'];

    // Handle image upload
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_dir =__DIR__ . '/../assets/images/';
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete the old image if new one is uploaded
            if ($currentImage && file_exists($target_dir . $currentImage)) {
                unlink($target_dir . $currentImage);
            }
        }
    } else {
        $image_name = $currentImage; // Retain current image if no new image is uploaded
    }

    // Update query
    $stmt = $conn->prepare("UPDATE properties 
        SET title = ?, 
            location = ?, 
            price = ?, 
            description = ?, 
            image_url = ?, 
            age = ?, 
            floor_plan = ?, 
            bedrooms = ?, 
            bathrooms = ?, 
            garden = ?, 
            parking = ?, 
            proximity_facilities = ?, 
            proximity_roads = ?, 
            property_tax = ? 
        WHERE id = ? AND user_id = ?");
    $stmt->bind_param(
        "ssdssiisiisssdii",
        $title,
        $location,
        $price,
        $description,
        $image_name,
        $age,
        $floor_plan,
        $bedrooms,
        $bathrooms,
        $garden,
        $parking,
        $proximity_facilities,
        $proximity_roads,
        $property_tax,
        $update_id,
        $_SESSION['user_id']
    );

    if ($stmt->execute()) {
        header("Location: seller_dashboard.php?message=Property updated successfully");
        exit();
    } else {
        die("Error executing query: " . $stmt->error);
    }
}


// Fetch all properties created by the seller
$stmt = $conn->prepare("SELECT * FROM properties WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Seller Dashboard - PropertY-Hub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script  type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('../assets/images/background.jpg') no-repeat center center/cover;
            font-family: 'Georgia', serif;
            color: #fff;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.8);
        }
        .navbar-brand {
            font-size: 2rem;
            color: gold !important;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
            height: 100%;
        }
        .card.add-card {
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: gold;
            background: rgba(0, 0, 0, 0.7);
            font-size: 3rem;
            font-weight: bold;
        }
        .modal-header {
            background-color: gold;
            color: black;
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


.modal-footer {
    background-color: #f8f9fa;
}

.form-control {
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.modal-body label {
    font-weight: bold;
    color: #333;
}

button.btn-primary {
    background-color: gold;
    border: none;
    color: black;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
}

button.btn-primary:hover {
    background-color: #d4af37; /* Darker gold */
    color: white;
}

button.btn-secondary {
    background-color: #6c757d;
    color: white;
}

button.btn-secondary:hover {
    background-color: #5a6268;
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PropertY-Hub</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center">Manage Your Properties</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="row">
            <?php if ($result->num_rows === 0): ?>
                <div class="col-md-4">
                    <div class="card add-card" data-bs-toggle="modal" data-bs-target="#addPropertyModal">
                        +
                    </div>
                </div>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 py-2">
                        <div class="card mb-4">
                            <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="Property Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                                <p class="card-text">Price: $<?= htmlspecialchars($row['price']) ?></p>
                                <p class="card-text">Description: <?= htmlspecialchars($row['description']) ?></p>
                                <button class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#propertyDetailsModal<?= $row['id'] ?>">View More</button>
                                <div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePropertyModal<?= $row['id'] ?>">Edit</button>
                                <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                                
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

                    </div>

                    <!-- Update Property Modal -->
                    <div class="modal fade" id="updatePropertyModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="seller_dashboard.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Property</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="property_id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="title-<?= $row['id'] ?>" class="form-label">Title</label>
                                            <input type="text" name="title" id="title-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="location-<?= $row['id'] ?>" class="form-label">Location</label>
                                            <input type="text" name="location" id="location-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['location']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price-<?= $row['id'] ?>" class="form-label">Price</label>
                                            <input type="number" name="price" id="price-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['price']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description-<?= $row['id'] ?>" class="form-label">Description</label>
                                            <textarea name="description"  id="description-<?= $row['id'] ?>" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="age-<?= $row['id'] ?>" class="form-label">Property Age (Years)</label>
                                            <input type="number" name="age" id="age-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['age']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="floor_plan-<?= $row['id'] ?>" class="form-label">Floor Plan (Square Footage)</label>
                                            <input type="number" name="floor_plan" id=floor_plan-<?= $row['id'] ?> class="form-control" value="<?= htmlspecialchars($row['floor_plan']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bedrooms-<?= $row['id'] ?>" class="form-label">Number of Bedrooms</label>
                                            <input type="number" name="bedrooms" id=bedrooms-<?= $row['id'] ?> class="form-control" value="<?= htmlspecialchars($row['bedrooms']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bathrooms-<?= $row['id'] ?>" class="form-label">Number of Bathrooms</label>
                                            <input type="number" name="bathrooms" id="bathrooms-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['bathrooms']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="garden-<?= $row['id'] ?>" class="form-label">Presence of a Garden</label>
                                            <select name="garden" class="form-control" required id="garden-<?= $row['id'] ?>">
                                                <option value="yes" <?= $row['garden'] === 'yes' ? 'selected' : '' ?>>Yes</option>
                                                <option value="no" <?= $row['garden'] === 'no' ? 'selected' : '' ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="parking-<?= $row['id'] ?>" class="form-label">Parking Availability</label>
                                            <select name="parking" class="form-control" id="parking-<?= $row['id'] ?>" required>
                                                <option value="yes" <?= $row['parking'] === 'yes' ? 'selected' : '' ?>>Yes</option>
                                                <option value="no" <?= $row['parking'] === 'no' ? 'selected' : '' ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="proximity_facilities-<?= $row['id'] ?>" class="form-label">Proximity to Nearby Facilities</label>
                                            <textarea name="proximity_facilities"  id="proximity_facilities-<?= $row['id'] ?>" class="form-control" required><?= htmlspecialchars($row['proximity_facilities']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="proximity_roads-<?= $row['id'] ?>" class="form-label">Proximity to Main Roads</label>
                                            <textarea name="proximity_roads" id="proximity_roads"  class="form-control" required><?= htmlspecialchars($row['proximity_roads']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="property_tax-<?= $row['id'] ?>" class="form-label">Property Tax (7% of Value)</label>
                                            <input type="number" name="property_tax" id="property_tax-<?= $row['id'] ?>" class="form-control" value="<?= htmlspecialchars($row['property_tax']) ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image-<?= $row['id'] ?>" class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control">
                                            <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" class="img-fluid mt-2">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_property" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
                <div class="col-md-4 py-2">
                    <div class="card add-card" data-bs-toggle="modal" data-bs-target="#addPropertyModal">
                        +
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Property Modal -->
    <div class="modal fade" id="addPropertyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="seller_dashboard.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter the property title" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter the property location" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Enter the price in USD" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter a brief description of the property" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Property Age (Years)</label>
                        <input type="number" name="age" id="age" class="form-control" placeholder="Enter the age of the property" required>
                    </div>
                    <div class="mb-3">
                        <label for="floor_plan" class="form-label">Floor Plan (Square Footage)</label>
                        <input type="number" name="floor_plan" id="floor_plan" class="form-control" placeholder="Enter the floor plan area in sq ft" required>
                    </div>
                    <div class="mb-3">
                        <label for="bedrooms" class="form-label">Number of Bedrooms</label>
                        <input type="number" name="bedrooms" id="bedrooms" class="form-control" placeholder="Enter the number of bedrooms" required>
                    </div>
                    <div class="mb-3">
                        <label for="bathrooms" class="form-label">Number of Bathrooms</label>
                        <input type="number" name="bathrooms" id="bathrooms" class="form-control" placeholder="Enter the number of bathrooms" required>
                    </div>
                    <div class="mb-3">
                        <label for="garden" class="form-label">Presence of a Garden</label>
                        <select name="garden" id="garden" class="form-control" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parking" class="form-label">Parking Availability</label>
                        <select name="parking" id="parking" class="form-control" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="proximity_facilities" class="form-label">Proximity to Nearby Facilities</label>
                        <textarea name="proximity_facilities" id="proximity_facilities" class="form-control" rows="2" placeholder="E.g., schools, colleges, towns" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proximity_roads" class="form-label">Proximity to Main Roads</label>
                        <textarea name="proximity_roads" id="proximity_roads" class="form-control" rows="2" placeholder="E.g., highways, major routes" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="property_tax" class="form-label">Property Tax (7% of Value)</label>
                        <input type="number" name="property_tax" id="property_tax" class="form-control" placeholder="Auto-calculated" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_property" class="btn btn-primary">Add Property</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<script>
    const priceInput = document.getElementById('price');
    const taxInput = document.getElementById('property_tax');
    priceInput.addEventListener('input', () => {
        const price = parseFloat(priceInput.value) || 0;
        console.log(price)
        const tax = (price * 0.07).toFixed(2); // Calculate 7% of the price
        console.log(taxInput)
        taxInput.value = tax;
    });
</script>
</body>
</html>
