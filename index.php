<<<<<<< HEAD
<?php
require_once 'includes/header.php';
error_reporting(E_ERROR | E_PARSE);
?>
=======
<!DOCTYPE html>
<html lang="en">
>>>>>>> 9a3f832d4663d05aff55a1f5365c801ba15d9c2c
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JIS GEPO Website</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
<<<<<<< HEAD
    <script src="assets/js/main.js"></script>

    <!-- Bootstrap 5 CDN Links -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Bootstrap 5 JS CDN Links -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>
<main>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand navbar-logo" href="#">
                <i class="fas fa-globe-asia me-2"></i><img src="assets/img/jisgrouplogo.png" alt="jis group logo" width="100px" height="100px" style="float:left; margin-left: -50px; margin-right: -60px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programs">
                            <i class="fas fa-graduation-cap me-1"></i>About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#partners">
                            <i class="fas fa-handshake me-1"></i>Global Partnerships
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#events">
                            <i class="fas fa-calendar-alt me-1"></i>Programs and Initiatives
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Resources and Support
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Events and News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


<section id="home" class="banner_wrapper p-0">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide" style="background-image: url(./assets/img/1.jpg);">
        <div class="slide-caption text-center">
        <div>
            <h1 class="animate-slide-up">Your Gateway to Global Collaboration and International Oppurtunities</h1>
            <p class="animate-fade-in">Empowering International Partnerships & Educational Excellence</p>
            <div class="cta-buttons">
                <a href="partner.php" class="btn btn-primary animate-bounce">Partner with Us</a>
                <a href="programs.php" class="btn btn-primary animate-bounce">Join a Program</a>
                <a href="partner.php" class="btn btn-primary animate-bounce">Contact Us</a>
            </div>
        </div>
      </div>
      </div>
      <div class="swiper-slide" style="background-image: url(./assets/img/2.jpg);">
        <div class="slide-caption text-center">
        <div>
          <h1>Welcome to Dubai</h1>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eaque, 
            architecto! Vel quo distinctio, debitis asperiores nihil quibusdam 
            blanditiis error sapiente ducimus accusamus! Ex illum culpa perspiciatis odit quo rem iure.</p>
        </div>
      </div>
      </div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>

    <!-- Animated Hero Section -->
    <!--<section class="hero-section">
        <div class="hero-content">
            <h1 class="animate-slide-up">Your Gateway to Global Collaboration and International Oppurtunities</h1>
            <p class="animate-fade-in">Empowering International Partnerships & Educational Excellence</p>
            <div class="cta-buttons">
                <a href="partner.php" class="btn btn-primary animate-bounce">Partner with Us</a>
                <a href="programs.php" class="btn btn-primary animate-bounce">Join a Program</a>
                <a href="partner.php" class="btn btn-primary animate-bounce">Contact Us</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="globe-animation"></div>
        </div>
    </section>-->

    <!-- Quick Links with Hover Effects -->
    <section class="quick-links">
        <div class="container">
            <div class="links-grid">
                <?php
                $quickLinks = [
                    ['icon' => 'graduation-cap', 'title' => 'Study Abroad', 'url' => 'programs.php#study-abroad'],
                    ['icon' => 'users', 'title' => 'International Admissions', 'url' => 'admissions.php'],
                    ['icon' => 'exchange', 'title' => 'Faculty Exchange Programs', 'url' => 'programs.php#faculty'],
                    ['icon' => 'microscope', 'title' => 'Research Collaborations', 'url' => 'research.php'],
                    ['icon' => 'calendar', 'title' => 'Upcoming International Events', 'url' => 'events.php']
                ];

                foreach ($quickLinks as $link) {
                    echo '<div class="link-card hover-lift">';
                    echo '<i class="fas fa-' . $link['icon'] . '"></i>';
                    echo '<h3>' . $link['title'] . '</h3>';
                    echo '<a href="' . $link['url'] . '" class="stretched-link"></a>';
                    echo '</div>';
                }
                ?>
=======
</head>
<body>
    <?php include 'programs.php'; ?>
    <main>
        <!-- Animated Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="animate-slide-up">Your Gateway to Global Collaboration</h1>
                <p class="animate-fade-in">Empowering International Partnerships & Educational Excellence</p>
                <div class="cta-buttons">
                    <a href="partner.php" class="btn btn-primary animate-bounce">Partner with Us</a>
                    <a href="programs.php" class="btn btn-secondary animate-bounce-delay">Join a Program</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="globe-animation"></div>
>>>>>>> 9a3f832d4663d05aff55a1f5365c801ba15d9c2c
            </div>
        </section>

<<<<<<< HEAD
    <!-- Interactive Global Partners Map -->
    <!-- <section class="partners-map">
        <h2>Our Global Network</h2>
        <div id="world-map"></div>
    </section> -->

    <!-- Latest News & Events -->
    <!-- <section class="news-events">
        <div class="container">
            <div class="section-header">
                <h2>Latest Updates</h2>
                <a href="events.php" class="view-all">View All</a>
            </div>
            <div class="news-grid">
                <?php include 'includes/latest-news.php'; ?>
            </div>
        </div>
    </section>-->

</main>
<?php
require_once 'includes/footer.php';
?>
=======
        <!-- Quick Links with Hover Effects -->
        <section class="quick-links">
            <div class="container">
                <div class="links-grid">
                    <?php
                    $quickLinks = [
                        ['icon' => 'graduation-cap', 'title' => 'Study Abroad', 'url' => 'programs.php#study-abroad'],
                        ['icon' => 'users', 'title' => 'International Admissions', 'url' => 'admissions.php'],
                        ['icon' => 'exchange', 'title' => 'Faculty Exchange', 'url' => 'programs.php#faculty'],
                        ['icon' => 'microscope', 'title' => 'Research Collaborations', 'url' => 'research.php'],
                        ['icon' => 'calendar', 'title' => 'International Events', 'url' => 'events.php']
                    ];

                    foreach ($quickLinks as $link) {
                        echo '<div class="link-card hover-lift">';
                        echo '<i class="fas fa-' . $link['icon'] . '"></i>';
                        echo '<h3>' . $link['title'] . '</h3>';
                        echo '<a href="' . $link['url'] . '" class="stretched-link"></a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Interactive Global Partners Map -->
        <section class="partners-map">
            <h2>Our Global Network</h2>
            <div id="world-map"></div>
        </section>

        <!-- Latest News & Events -->
        <section class="news-events">
            <div class="container">
                <div class="section-header">
                    <h2>Latest Updates</h2>
                    <a href="events.php" class="view-all">View All</a>
                </div>
                <div class="news-grid">
                    <?php include 'includes/latest-news.php'; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
    document.getElementById("menu-toggle").addEventListener("click", () => {
      const navMenu = document.querySelector(".navbar-menu");
      navMenu.style.display = navMenu.style.display === "none" ? "flex" : "none";
    });
    </script>

    <?php
    require_once 'includes/footer.php';
    ?>
</body>
</html>
>>>>>>> 9a3f832d4663d05aff55a1f5365c801ba15d9c2c
