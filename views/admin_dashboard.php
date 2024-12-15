<?php
session_start();
require '../php/db.php'; // Include the database connection

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch data for overview cards
$totalProperties = $conn->query("SELECT COUNT(*) AS count FROM properties");
if (!$totalProperties) die("Error in query: " . $conn->error);
$totalProperties = $totalProperties->fetch_assoc()['count'];

$totalBuyers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'buyer'");
if (!$totalBuyers) die("Error in query: " . $conn->error);
$totalBuyers = $totalBuyers->fetch_assoc()['count'];

$totalSellers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'seller'");
if (!$totalSellers) die("Error in query: " . $conn->error);
$totalSellers = $totalSellers->fetch_assoc()['count'];

// Fetch data for charts and lists
$topLocations = $conn->query("SELECT location, COUNT(*) AS count FROM properties GROUP BY location ORDER BY count DESC LIMIT 5");
if (!$topLocations) die("Error in query: " . $conn->error);

$propertyStatus = $conn->query("SELECT status, COUNT(*) AS count FROM properties GROUP BY status");
if (!$propertyStatus) die("Error in query: " . $conn->error);

$topUsers = $conn->query("SELECT u.username, COUNT(p.id) AS properties 
                          FROM users u 
                          JOIN properties p ON u.id = p.user_id 
                          GROUP BY u.username 
                          ORDER BY properties DESC 
                          LIMIT 5");
if (!$topUsers) die("Error in query: " . $conn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - PropertY-Hub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="../assets/js/chart.js"></script>
    <style>
        .card {
            background-color: #f8f9fa;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .card h3 {
            font-size: 2.5rem;
            color: #333;
        }
        .card p {
            font-size: 1.2rem;
            color: #666;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar {
            background-color: #343a40;
            color: #fff;
        }
        .navbar .navbar-brand {
            color: gold;
            font-weight: bold;
        }
        .navbar .btn {
            font-weight: bold;
            border-radius: 5px;
        }
    </style>
   
<script>
    // Data for Properties by Location
    const locationLabels = [
        <?php
        while ($row = $topLocations->fetch_assoc()) {
            echo "'" . $row['location'] . "',";
        }
        ?>
    ];

    const locationData = [
        <?php
        $topLocations->data_seek(0); // Reset the pointer to loop through again
        while ($row = $topLocations->fetch_assoc()) {
            echo $row['count'] . ",";
        }
        ?>
    ];

    // Data for Properties by Status
    const statusLabels = [
        <?php
        while ($row = $propertyStatus->fetch_assoc()) {
            echo "'" . $row['status'] . "',";
        }
        ?>
    ];

    const statusData = [
        <?php
        $propertyStatus->data_seek(0); // Reset the pointer to loop through again
        while ($row = $propertyStatus->fetch_assoc()) {
            echo $row['count'] . ",";
        }
        ?>
    ];

    // Function to draw charts
    document.addEventListener('DOMContentLoaded', function() {
        // Properties by Location Chart
        const locationCtx = document.getElementById('location_chart').getContext('2d');
        new Chart(locationCtx, {
            type: 'pie',
            data: {
                labels: locationLabels,
                datasets: [{
                    label: 'Properties by Location',
                    data: locationData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: 'Properties by Location' }
                }
            }
        });

        // Properties by Status Chart
        const statusCtx = document.getElementById('status_chart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Properties by Status',
                    data: statusData,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: 'Properties by Status' }
                }
            }
        });
    });
</script>

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

    <div class="container mt-5">
        <h1 class="text-center">Admin Dashboard</h1>

        <!-- Overview Cards -->
        <div class="row my-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?= $totalProperties ?></h3>
                        <p>Total Properties</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?= $totalBuyers ?></h3>
                        <p>Total Buyers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?= $totalSellers ?></h3>
                        <p>Total Sellers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
            <canvas id="location_chart" width="400" height="400"></canvas>
            </div>
            <div class="col-md-6">
            <canvas id="status_chart" width="400" height="400"></canvas>
            </div>
        </div>

        <!-- Top Locations and Users -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h3>Top Locations</h3>
                <ul class="list-group">
                    <?php
                    $topLocations->data_seek(0); // Reset pointer to reuse result set
                    while ($row = $topLocations->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($row['location']) ?>
                            <span class="badge bg-primary rounded-pill"><?= $row['count'] ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h3>Top Users</h3>
                <ul class="list-group">
                    <?php while ($row = $topUsers->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($row['username']) ?>
                            <span class="badge bg-primary rounded-pill"><?= $row['properties'] ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
