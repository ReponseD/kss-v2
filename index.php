<?php
/**
 * Homepage - Dynamic Version
 * KSS CMS
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/functions.php';

// Get homepage content from database
$db = getDB();

// Get homepage sections
$sectionsStmt = $db->query("SELECT * FROM homepage_sections WHERE is_active = 1 ORDER BY display_order ASC");
$sections = $sectionsStmt->fetchAll();
$homepage = [];
foreach ($sections as $section) {
    $homepage[$section['section_key']] = $section['content'];
}

// Get homepage banners
$bannersStmt = $db->query("SELECT * FROM homepage_banners WHERE is_active = 1 ORDER BY display_order ASC");
$banners = $bannersStmt->fetchAll();

// Get featured news/announcements for homepage
$featuredContent = $db->query("
    SELECT c.*, u.full_name as author_name, cat.name as category_name
    FROM content c
    LEFT JOIN users u ON c.author_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.status = 'published' AND c.is_featured = 1
    ORDER BY c.published_at DESC
    LIMIT 3
")->fetchAll();

// Get featured gallery images
$featuredGallery = $db->query("
    SELECT * FROM gallery 
    WHERE is_featured = 1
    ORDER BY display_order ASC, created_at DESC
    LIMIT 4
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8">
    <meta name="description" content="Kagarama Secondary School in Kigali, Rwanda, offers quality education, modern facilities, and a nurturing environment for students.">
    <meta name="keywords" content="Kagarama Secondary School, Kigali, Rwanda, Education, Secondary School, High School, Academic Excellence">
    <link rel="canonical" href="https://www.kagaramasec.org/">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="kss-logo-v4.png">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&amp;family=Roboto:wght@500;700;900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Kagarama Secondary School | Home</title>
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="kss-logo-v4.png" alt="Kagarama secondary school Logo" style="max-height:70px;" loading="lazy">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Our School</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="About.html" class="dropdown-item">About KSS</a>
                        <a href="Staff.html" class="dropdown-item">Principal's Message</a>
                        <a href="Facilities.html" class="dropdown-item">Facilities</a>
                        <a href="Staff.html" class="dropdown-item">Our Staff</a>
                    </div>
                </div>
                <a href="Admissions.html" class="nav-item nav-link">Admissions</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Academic Life</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="Staff.html" class="dropdown-item">Academic Staff</a>
                        <a href="Program.html" class="dropdown-item">Programs</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Updates</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="Updates.html" class="dropdown-item">All Updates</a>
                        <a href="Updates.html?type=news" class="dropdown-item">News</a>
                        <a href="Updates.html?type=blog" class="dropdown-item">Blogs</a>
                        <a href="Updates.html?type=announcement" class="dropdown-item">Announcements</a>
                        <a href="Gallery.html" class="dropdown-item">Gallery</a>
                    </div>
                </div>
                <a href="Contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="Admissions.html" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block" style="background-color: #a05d3c; color:#fff;">Apply Now<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Section Start -->
    <div class="container-fluid p-0 mb-5">
        <div class="owl-carousel header-carousel position-relative">
            <?php if (empty($banners)): ?>
                <!-- Default banner if none in database -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid w-100" src="b g.jpg" alt="Kagarama Secondary School" style="height:600px; object-fit:cover;" loading="lazy">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4));">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10 text-center wow fadeInUp">
                                    <h1 class="display-3 text-white mb-4 fw-bold"><?php echo htmlspecialchars($homepage['hero_title'] ?? 'Inspiring Excellence'); ?></h1>
                                    <h2 class="display-5 text-white mb-4"><?php echo htmlspecialchars($homepage['hero_subtitle'] ?? 'Service to God and the Nations'); ?></h2>
                                    <p class="lead text-white mb-5"><?php echo htmlspecialchars($homepage['hero_description'] ?? 'Kagarama Secondary School delivers a learner-centered education underpinned by established teaching skills and a strong school culture.'); ?></p>
                                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                                        <a href="Admissions.html" class="btn btn-lg btn-primary px-5 py-3" style="background-color: #a05d3c; border:none;">
                                            <i class="fa fa-graduation-cap me-2"></i>Apply Now
                                        </a>
                                        <a href="About.html" class="btn btn-lg btn-outline-light px-5 py-3">
                                            <i class="fa fa-info-circle me-2"></i>Discover More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($banners as $banner): ?>
                    <div class="owl-carousel-item position-relative">
                        <img class="img-fluid w-100" src="<?php echo htmlspecialchars($banner['image_url']); ?>" alt="<?php echo htmlspecialchars($banner['title'] ?? 'Kagarama Secondary School'); ?>" style="height:600px; object-fit:cover;" loading="lazy">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4));">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-lg-10 text-center wow fadeInUp">
                                        <?php if ($banner['title']): ?>
                                            <h1 class="display-3 text-white mb-4 fw-bold"><?php echo htmlspecialchars($banner['title']); ?></h1>
                                        <?php endif; ?>
                                        <?php if ($banner['subtitle']): ?>
                                            <h2 class="display-5 text-white mb-4"><?php echo htmlspecialchars($banner['subtitle']); ?></h2>
                                        <?php endif; ?>
                                        <?php if ($banner['description']): ?>
                                            <p class="lead text-white mb-5"><?php echo htmlspecialchars($banner['description']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($banner['button_text'] && $banner['button_link']): ?>
                                            <a href="<?php echo htmlspecialchars($banner['button_link']); ?>" class="btn btn-lg btn-primary px-5 py-3" style="background-color: #a05d3c; border:none;">
                                                <?php echo htmlspecialchars($banner['button_text']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- Hero Section End -->

    <script>
        $(document).ready(function() {
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                dots:true,
                items:1,
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true
            });
        });
    </script>

    <!-- Quick Links Section -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-4 text-center h-100" style="border-top: 4px solid #a05d3c;">
                        <i class="fa fa-graduation-cap fa-3x text-primary mb-3" style="color: #a05d3c;"></i>
                        <h5 class="mb-3">Admissions</h5>
                        <p class="mb-3">Join our community of excellence. Apply now for the upcoming academic year.</p>
                        <a href="Admissions.html" class="btn btn-primary btn-sm" style="background-color: #a05d3c; border:none;">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="bg-light rounded p-4 text-center h-100" style="border-top: 4px solid #a05d3c;">
                        <i class="fa fa-calendar-alt fa-3x text-primary mb-3" style="color: #a05d3c;"></i>
                        <h5 class="mb-3">School Calendar</h5>
                        <p class="mb-3">Stay updated with important dates, events, and academic schedules.</p>
                        <a href="Activities.html" class="btn btn-primary btn-sm" style="background-color: #a05d3c; border:none;">View Calendar</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="bg-light rounded p-4 text-center h-100" style="border-top: 4px solid #a05d3c;">
                        <i class="fa fa-users fa-3x text-primary mb-3" style="color: #a05d3c;"></i>
                        <h5 class="mb-3">Discover KSS</h5>
                        <p class="mb-3">Explore our facilities, programs, and vibrant student life.</p>
                        <a href="About.html" class="btn btn-primary btn-sm" style="background-color: #a05d3c; border:none;">Discover</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="bg-light rounded p-4 text-center h-100" style="border-top: 4px solid #a05d3c;">
                        <i class="fa fa-trophy fa-3x text-primary mb-3" style="color: #a05d3c;"></i>
                        <h5 class="mb-3">Achievements</h5>
                        <p class="mb-3">Celebrating our students' successes and school accomplishments.</p>
                        <a href="Activities.html" class="btn btn-primary btn-sm" style="background-color: #a05d3c; border:none;">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Links End -->

    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 col-sm-12 order-2 order-lg-1 about-text wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-3 pe-lg-0">
                        <div class="section-title text-start">
                            <h2 class="display-5 mb-4"><?php echo isset($homepage['welcome_title']) ? $homepage['welcome_title'] : 'Welcome to <strong>KSS</strong>'; ?></h2>
                        </div>
                        <p class="mb-4 pb-2"><?php echo isset($homepage['welcome_content']) ? nl2br(htmlspecialchars($homepage['welcome_content'])) : 'Kagarama Secondary School (KSS) is located in Kicukiro District, Kigali City, Rwanda. Since its establishment, KSS has been officially recognized by the Ministry of Education as one of the leading government-aided schools in the country.'; ?></p>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 ps-lg-0 order-1 order-lg-2" style="height: 500px;">
                    <div class="position-relative h-100">
                        <img src="https://media.licdn.com/dms/image/v2/D4D22AQG5bCZthZdyDQ/feedshare-shrink_800/B4DZcWV.pUIAAg-/0/1748437432667?e=2147483647&v=beta&t=F_mwi45Nfme7EqbQV_S0AiHRjJ2MQphP4Mx2cYUJy7E" alt="Background Image" class="img-fluid w-100 h-100" style="object-fit: cover; border-radius: 7px;" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Vision & Mission Section -->
    <div class="container-fluid position-relative" style="overflow: hidden; border-radius: 7px;">
        <div class="parallax-layer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('b g.jpg'); background-size: cover; background-position: center; transform: translateZ(0) scale(1.1);"></div>
        <div class="background-layer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(25, 39, 78, 0.842);"></div>
        <div class="container">
            <div class="row justify-content-center align-items-top" style="margin-top: 200px; color: #fff; position: relative; z-index: 2; height: auto;">
                <div class="col-md-6">
                    <h4 class="text-left" style="color: #fff;"><?php echo htmlspecialchars($homepage['vision_title'] ?? 'Our Vision'); ?></h4>
                    <p class="mb-4 pb-2"><?php echo htmlspecialchars($homepage['vision_content'] ?? 'To be a center of academic excellence that nurtures disciplined, innovative, and responsible citizens equipped with knowledge, skills, and values to contribute meaningfully to Rwanda and the global community.'); ?></p>
                </div>
                <div class="col-md-6">
                    <h4 class="text-left" style="color: #fff;"><?php echo htmlspecialchars($homepage['mission_title'] ?? 'Our Mission'); ?></h4>
                    <p class="mb-4 pb-2"><?php echo htmlspecialchars($homepage['mission_content'] ?? 'To provide quality education that empowers students with knowledge, skills, and values to excel and positively impact society.'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured News Section -->
    <?php if (!empty($featuredContent)): ?>
    <div class="container-xxl py-5 bg-light">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2 class="display-5 mb-3">Latest Updates</h2>
                <p class="lead text-muted">Stay up-to-date with the latest news and events</p>
            </div>
            <div class="row g-4">
                <?php foreach ($featuredContent as $item): ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="bg-white rounded overflow-hidden shadow-sm h-100">
                            <?php if ($item['featured_image']): ?>
                                <img class="img-fluid w-100" src="<?php echo htmlspecialchars($item['featured_image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="height:200px; object-fit:cover;" loading="lazy">
                            <?php else: ?>
                                <img class="img-fluid w-100" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&q=80" alt="News" style="height:200px; object-fit:cover;" loading="lazy">
                            <?php endif; ?>
                            <div class="p-4">
                                <div class="d-flex mb-2">
                                    <small class="text-primary me-3"><i class="fa fa-calendar me-1"></i> <?php echo date('F j, Y', strtotime($item['published_at'])); ?></small>
                                </div>
                                <h5 class="mb-3"><?php echo htmlspecialchars($item['title']); ?></h5>
                                <p class="mb-3"><?php echo htmlspecialchars($item['excerpt'] ?: substr(strip_tags($item['content']), 0, 100) . '...'); ?></p>
                                <a href="UpdateDetail.html?id=<?php echo $item['id']; ?>" class="text-primary">Read More <i class="fa fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer Start -->
    <div class="container-fluid bg-primary text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <img src="kss-logo-v4.png" alt="Kagarama Secondary School Logo" style="max-height:200px;">
                    <p class="mt-3">Kagarama Secondary School delivers a learner-centered education underpinned by established teaching skills and a strong school culture.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="About.html">About Us</a>
                    <a class="btn btn-link" href="Program.html">Programs</a>
                    <a class="btn btn-link" href="Admissions.html">Admissions</a>
                    <a class="btn btn-link" href="Staff.html">Our Staff</a>
                    <a class="btn btn-link" href="Contact.html">Contact</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Get in touch</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Kicukiro, Kigali, Rwanda</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><a href="tel:+250788566707" style="color:#fff">+250 788 566 707</a></p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><a href="mailto:info@kagaramasec.org" style="color:#fff">info@kagaramasec.org</a></p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Important Links</h4>
                    <a class="btn btn-link" href="Activities.html">School Activities</a>
                    <a class="btn btn-link" href="Facilities.html">Facilities</a>
                    <a class="btn btn-link" href="Gallery.html">Gallery</a>
                    <a class="btn btn-link" href="Staff.html">Principal's Message</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#"><script>document.write(new Date().getFullYear())</script> - Kagarama Secondary School</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Designed By <a class="border-bottom" href="https://www.siliconvalleyafrica.org/">Silicon Valley of Africa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-0 back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/counterup/counterup.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets/lib/isotope/isotope.pkgd.min.js"></script>
    <script src="assets/lib/lightbox/js/lightbox.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="//code.tidio.co/se12xxejs29lskmn8cekydq7lgbfaffx.js" async></script>
</body>
</html>

