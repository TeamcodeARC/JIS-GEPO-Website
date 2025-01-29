<?php
require_once 'includes/header.php';
?>
<head>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/enhanced-styles.css">
</head>

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
        </div>
    </section>

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