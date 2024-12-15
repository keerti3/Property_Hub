<?php
session_start();
require '../php/db.php'; // Include the database connection

// Ensure the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

$error_message = null;
if (isset($_GET['error'])) {
    $error_message = urldecode($_GET['error']); // Decode the error message
}



$user_id = $_SESSION['user_id'];
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND property_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $user_id, $remove_id);
        $stmt->execute();
    }
}

// Handle adding a property to the wishlist
if (isset($_GET['add_id'])) {
    $property_id = $_GET['add_id'];

    // Check if the property is already in the wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND property_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $user_id, $property_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Add the property to the wishlist if it doesn't already exist
            $stmt = $conn->prepare("INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ii", $user_id, $property_id);
                $stmt->execute();
            }
        }
    }
    header("Location: buyer_dashboard.php");
    exit();
}

// Fetch all wishlist items for the logged-in buyer
$stmt = $conn->prepare("SELECT p.* FROM wishlist w JOIN properties p ON w.property_id = p.id WHERE w.user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        die("Error executing query: " . $stmt->error);
    }
} else {
    die("Error preparing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist - PropertY-Hub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script  type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to detect credit card type and update logo
        function detectCardType(cardNumber) {
            //const cardTypeElement = document.getElementById("card-type");
            const cardLogoElement = document.getElementById("card-logo");

            //cardTypeElement.innerText = ""; // Clear previous detection
            cardLogoElement.src = ""; // Clear previous logo

            // Detect card type
            if (/^4/.test(cardNumber)) {
               // cardTypeElement.innerText = "Visa";
                cardLogoElement.src = "../assets/images/visa.jpg";
            } else if (/^5[1-5]/.test(cardNumber)) {
               // cardTypeElement.innerText = "MasterCard";
                cardLogoElement.src = "../assets/images/master.jpg";
            } else if (/^3[47]/.test(cardNumber)) {
               // cardTypeElement.innerText = "American Express";
                cardLogoElement.src = "../assets/images/americal.png";
            } else if (/^6(?:011|5[0-9]{2})/.test(cardNumber)) {
              //  cardTypeElement.innerText = "Discover";
                cardLogoElement.src = "../assets/images/discovery.png";
            } else {
              //  cardTypeElement.innerText = "Unknown Card Type";
                cardLogoElement.src = "../assets/images/card.png";
            }
        }

        // Function to set property details in Buy Now modal
        function setBuyNowDetails(propertyName, propertyPrice,propertyId) {
            console.log(propertyId)
            document.getElementById('property-details').innerText = `You are purchasing: ${propertyName} for $${propertyPrice}`;
            document.getElementById('price').value = propertyPrice; // Set the price in the hidden input
            document.getElementById('property_id').value = propertyId;
        }
    </script>
    <style>
        #card-type {
            font-size: 1rem;
            color: gold;
            margin-left: 10px;
            font-weight: bold;
        }
        #card-logo {
            margin-left: 10px;
            width: 40px;
            height: 25px;
        }
        .form-control.card-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .form-control.card-input {
            flex: 1;
        }
        /* Navbar General Styling */
.navbar {
    background-color: rgba(0, 0, 0, 0.9); /* Dark background */
    padding: 15px 20px;
    border-bottom: 2px solid gold; /* Stylish gold border at the bottom */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Navbar Brand */
.navbar .navbar-brand {
    font-size: 1.8rem;
    color: gold; /* Luxury-themed text color */
    font-family: 'Georgia', serif; /* Elegant font */
    font-weight: bold;
    text-decoration: none;
}

.navbar .navbar-brand:hover {
    color: #d4af37; /* Slightly darker gold on hover */
    text-decoration: none;
}

/* Navbar Buttons */
.navbar .btn {
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    padding: 10px 20px;
    transition: all 0.3s ease-in-out;
}

/* Dashboard Button */
.navbar .btn-secondary {
    background-color: #6c757d; /* Standard secondary color */
    color: white;
    border: none;
}

.navbar .btn-secondary:hover {
    background-color: #5a6268; /* Darker gray on hover */
    color: white;
}

/* Logout Button */
.navbar .btn-danger {
    background-color: #dc3545; /* Bootstrap danger color */
    color: white;
    border: none;
}

.navbar .btn-danger:hover {
    background-color: #b02a37; /* Darker red on hover */
    color: white;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .navbar .btn {
        font-size: 0.9rem;
        padding: 8px 15px;
    }

    .navbar .navbar-brand {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PropertY-Hub</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="buyer_dashboard.php" class="btn btn-secondary me-2">Dashboard</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <div class="container mt-5">
        <h1>Your Wishlist</h1>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="Property Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                            <p class="card-text">Price: $<?= htmlspecialchars($row['price']) ?></p>
                            <p class="card-text">Description: <?= htmlspecialchars($row['description']) ?></p>
                            <a href="?remove_id=<?= $row['id'] ?>" class="btn btn-danger">Remove from Wishlist</a>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buyNowModal" 
                                onclick="setBuyNowDetails('<?= htmlspecialchars($row['title']) ?>', '<?= htmlspecialchars($row['price']) ?>', '<?= htmlspecialchars($row['id']) ?>' )">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Buy Now Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyNowModalLabel">Buy Now</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../php/process-payment.php" method="POST">
                    <div class="modal-body">
                        <p id="property-details"></p>
                        <input type="hidden" id="price" name="price">
                        <input type="hidden" id="property_id" name="property_id">
                        <div class="form-group mb-3 form-control card-input-wrapper">
                            <input type="text" class="form-control card-input" name="card_number" placeholder="Credit Card Number" oninput="detectCardType(this.value)" required>
                            <img id="card-logo" src="../assets/images/card.png" alt="Card Logo">
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="name_on_card" placeholder="Name on Card" required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="expiration_date" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="cvv" placeholder="CVV" required>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
