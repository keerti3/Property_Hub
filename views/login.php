<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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


    <!-- Login Form -->
    <div class="auth-container">
        <h2>Login</h2>
        <form action="../php/login.php" method="POST">
            <input type="email" name="username" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up here</a></p>
    </div>
</body>
</html>
