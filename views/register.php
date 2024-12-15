<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="home.php">
            <img src="../assets/images/logo.png" alt="Logo">
            PropertY-Hub
        </a>
        <!-- Home Button -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link btn btn-outline-light" href="home.php">Home</a>
            </li>
        </ul>
    </div>
</nav>


    <!-- Register Form -->
    <div class="auth-container">
        <h2>Register</h2>
        
        <form action="../php/register.php" method="POST">
            <div class="form-group mb-3">
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group mb-3">
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group mb-3">
                
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group mb-3">
                <select class="form-control" id="role" name="role" required>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
