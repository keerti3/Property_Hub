<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to PropertY-Hub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
/* General Styles */

body {
            margin: 0;
            font-family: 'Georgia', serif;
            color: #333;
        }

h2 {
    font-size: 3rem;
    color: white;
    margin-bottom: 20px;
    text-align: center;
}

p {
    font-size: 1.2rem;
    line-height: 1.8;
    color: white;
}

/* Navbar */
.navbar {
    background: rgba(0, 0, 0, 0.9);
}

.navbar-brand {
    font-size: 2rem;
    color: gold !important;
    font-weight: bold;
}

.navbar-brand img {
    height: 50px;
    margin-right: 10px;
}

.navbar-nav .nav-link {
    font-size: 1.2rem;
    padding: 8px 20px;
    border-radius: 5px;
    transition: all 0.3s;
}

.navbar-nav .btn-outline-light {
    border: 2px solid gold;
    color: gold;
}

.navbar-nav .btn-outline-light:hover {
    background-color: gold;
    color: black;
}

.navbar-nav .btn-light {
    background-color: gold;
    color: black;
}

.navbar-nav .btn-light:hover {
    background-color: #d4af37;
    color: white;
}

/* Hero Section */
.hero-section {
    position: relative;
    background: url('../assets/images/background.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-section .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 20px;
}

.hero-content h1 {
    font-size: 4.5rem;
    color: gold;
    text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7);
}

.hero-content p {
    font-size: 1.8rem;
    margin-bottom: 30px;
}

.hero-content .btn {
    background-color: gold;
    color: black;
    font-size: 1.2rem;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    transition: all 0.3s;
}

.hero-content .btn:hover {
    background-color: #d4af37;
    color: white;
}

/* Carousel Section */
.carousel-item img {
    height: 600px;
    object-fit: cover;
}

.carousel-caption {
    background: rgba(0, 0, 0, 0.6);
    padding: 10px;
    border-radius: 5px;
    color: white;
}

/* Features Section */
.features-section {
    padding: 60px 20px;
    text-align: center;
    background: #f8f9fa;
}

.features-section h2 {
    font-size: 3rem;
    color: gold;
    margin-bottom: 30px;
}
.features-section p {
    color: #333;
}

.features {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.feature {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    padding: 20px;
    width: 300px;
    text-align: center;
    transition: transform 0.3s;
}

.feature img {
    height: 150px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 10px;
}

.feature:hover {
    transform: translateY(-10px);
}
/* General Section Styling */
.content-section {
    padding: 60px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
}

.content-section img {
    width: 50%; /* Ensures images have the same size */
    height: 350px; /* Fixed height for uniformity */
    object-fit: cover; /* Keeps the image proportions */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.content-text {
    max-width: 50%;
}

.content-text h2 {
    font-size: 3rem;
    color: gold;
    margin-bottom: 20px;
}

.content-text p {
    font-size: 1.2rem;
    line-height: 1.8;
    color: #333;
}

/* About Us Specific */
.about-section {
    background: #f8f9fa;
    flex-direction: row;
}

/* Why Choose Us Specific */
.why-choose-us {
    background: #e9ecef;
}

.why-choose-us ul {
    font-size: 1.2rem;
    line-height: 1.8;
    color: #333;
}

/* Footer */
.footer {
    background: rgba(0, 0, 0, 0.9);
    padding: 20px 0;
    text-align: center;
    color: gold;
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../assets/images/logo.png" alt="Logo">
                PropertY-Hub
            </a>
        </div>
    </nav> -->

    <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../assets/images/logo.png" alt="Logo">
            PropertY-Hub
        </a>
        <!-- Add a toggle button for mobile responsiveness -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light me-2" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light" href="register.php">Signup</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <!-- Hero Section -->
    <div class="hero-section">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Welcome to PropertY-Hub</h1>
            <p>Your destination for luxurious properties and exceptional service.</p>
            <a href="register.php" class="btn">Get Started</a>
        </div>
    </div>

    <!-- Carousel Section -->
    <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../assets/images/2-1.gif" class="d-block w-100" alt="Luxury Home Tour">
                <div class="carousel-caption">
                    <h5>Luxury Home Tours</h5>
                    <p>Explore exclusive properties with virtual tours.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../assets/images/3.gif" class="d-block w-100" alt="Modern Villas">
                <div class="carousel-caption">
                    <h5>Modern Villas</h5>
                    <p>Find your dream home among our stunning villa listings.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../assets/images/4.gif" class="d-block w-100" alt="Exclusive Apartments">
                <div class="carousel-caption">
                    <h5>Exclusive Apartments</h5>
                    <p>Step into luxurious apartments with breathtaking views.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <h2>Our Services</h2>
            <div class="features">
                <div class="feature">
                    <img src="../assets/images/2.gif" alt="Premium Listings">
                    <h3>Premium Listings</h3>
                    <p>Access a wide range of exclusive properties tailored to your preferences.</p>
                </div>
                <div class="feature">
                    <img src="../assets/images/3.gif" alt="Virtual Tours">
                    <h3>Virtual Tours</h3>
                    <p>Explore properties from the comfort of your home with immersive tours.</p>
                </div>
                <div class="feature">
                    <img src="../assets/images/4.gif" alt="Investment Insights">
                    <h3>Investment Insights</h3>
                    <p>Get expert advice and market trends for informed investment decisions.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- About Us Section -->
<div class="content-section about-section">
    <div class="content-text">
        <h2>About Our Project</h2>
        <p>
            <strong>PropertY-Hub</strong> is a revolutionary platform designed to simplify property transactions. 
            Whether you're buying your dream home, selling your property, or seeking lucrative investment opportunities, 
            <strong>PropertY-Hub</strong> provides you with a user-friendly interface and access to premium listings.
        </p>
        <p>
            Our mission is to bring innovation to the real estate industry by combining technology with personalized customer service.
        </p>
    </div>
    <img src="../assets/images/about-us.jpg" alt="About Us Image">
</div>

<!-- Why Choose Us Section -->
<div class="content-section why-choose-us">
    <img src="../assets/images/why-us.jpg" alt="Why Choose Us Image">
    <div class="content-text">
        <h2>Why Choose Us?</h2>
        <p>
            At <strong>PropertY-Hub</strong>, we offer unmatched luxury, transparency, and professionalism. Here's why thousands trust us:
        </p>
        <ul>
            <li>Exclusive property listings tailored to your preferences.</li>
            <li>Virtual tours and cutting-edge features to simplify your search.</li>
            <li>Expert advice and 24/7 customer support for peace of mind.</li>
        </ul>
        <p>
            Join our growing community and experience the <strong>PropertY-Hub</strong> difference today.
        </p>
    </div>
</div>


    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 PropertY-Hub. All rights reserved.</p>
    </footer>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
