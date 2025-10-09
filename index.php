<?php
require_once 'config/functions.php';

// Redirect resident role to dashboard
if (class_exists('MultiRoleSessionManager') && MultiRoleSessionManager::isRoleLoggedIn('resident')) {
    header('Location: user/dashboard.php');
    exit();
}

$success_message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'logged_out':
            $success_message = 'You have been logged out successfully.';
            break;
        case 'registration_success':
            $success_message = 'Registration successful! Please wait for admin approval before logging in.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ServeIT - Barangay Manggahan Services</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/serveit.css" rel="stylesheet" />
    <style>
      .navbar-logo { height: 36px; width: 36px; object-fit: contain; }
    </style>
</head>
<body>
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; max-width: 500px;" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
                <img src="assets/images/manggahan_logo_no_bg.png" alt="Barangay Manggahan Logo" class="navbar-logo me-1" />
                <span>ServeIT</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#announcements">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-login ms-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">Welcome to ServeIT</h1>
                        <p class="hero-subtitle">Your digital gateway to Barangay Manggahan services. Request documents, file grievances, and stay updated with barangay announcements - all in one convenient platform.</p>
                        <div>
                            <a href="register.php" class="btn btn-cta me-3">
                                <i class="fas fa-user-plus me-2"></i>Register Now
                            </a>
                            <a href="#services" class="btn btn-outline-light">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual text-center">
                        <div class="hero-icon-container">
                            <i class="fas fa-city hero-main-icon"></i>
                            <div class="floating-elements">
                                <div class="floating-icon floating-icon-1"><i class="fas fa-file-alt"></i></div>
                                <div class="floating-icon floating-icon-2"><i class="fas fa-users"></i></div>
                                <div class="floating-icon floating-icon-3"><i class="fas fa-cogs"></i></div>
                                <div class="floating-icon floating-icon-4"><i class="fas fa-shield-alt"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title text-primary-custom">Our Services</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-file-alt"></i></div>
                        <h3 class="service-title">Document Requests</h3>
                        <p class="service-description">Request barangay documents online including clearances, certificates, and permits. Track your requests in real-time and choose convenient pickup locations.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <h3 class="service-title">Grievance Management</h3>
                        <p class="service-description">File complaints and concerns securely. Choose to submit anonymously and track the resolution progress of your grievances through our system.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-bullhorn"></i></div>
                        <h3 class="service-title">Announcements</h3>
                        <p class="service-description">Stay informed with the latest barangay news, service updates, holiday schedules, and important community announcements.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3 class="service-title">Multiple Annexes</h3>
                        <p class="service-description">Services available across all annexes: Manggahan Proper, Karangalan, Green Park, and Napico. Choose your preferred location for document pickup.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-clock"></i></div>
                        <h3 class="service-title">24/7 Access</h3>
                        <p class="service-description">Submit requests anytime, anywhere. Our online platform is available 24/7 for your convenience, making barangay services accessible at your fingertips.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
                        <h3 class="service-title">Secure Platform</h3>
                        <p class="service-description">Your personal information is protected with advanced security measures. All transactions and communications are encrypted for your safety and privacy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="announcements" class="announcements-section">
        <div class="container">
            <h2 class="section-title text-primary-custom">Latest Announcements</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="announcement-card">
                        <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 25, 2025</div>
                        <h4 class="mt-2">ServeIT System Now Live!</h4>
                        <p class="mb-0">We are excited to announce the launch of our new online service platform. Residents can now request documents and file grievances digitally.</p>
                    </div>
                    <div class="announcement-card">
                        <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 24, 2025</div>
                        <h4 class="mt-2">Holiday Schedule Notice</h4>
                        <p class="mb-0">Please be advised that all barangay offices will be closed on August 30, 2025 (National Heroes Day). Online services will remain available.</p>
                    </div>
                    <div class="announcement-card">
                        <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 20, 2025</div>
                        <h4 class="mt-2">Document Fee Updates</h4>
                        <p class="mb-0">Updated fees for business permits and construction permits are now in effect. Please check our services section for current rates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title text-primary-custom">About Barangay Manggahan</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="about-card">
                        <h3 class="text-primary-custom mb-3"><i class="fas fa-home me-2"></i>Our Community</h3>
                        <p>Barangay Manggahan is one of the 30 barangays in Pasig City. Manggahan is a barangay located in Pasig City and it is a part of District 2 in Pasig. Barangay Manggahan is led by the Barangay Captain, Kap Quin "Kin" Cruz.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="about-card">
                        <h3 class="text-primary-custom mb-3"><i class="fas fa-target me-2"></i>Our Mission</h3>
                        <p>We commit to perform better duties and responsibilities to carry out plans and objectives of the barangay and the constituents shall work all together to pursue their programs, to facilitate positivity of the changes in quality standards.</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-3 col-6 text-center mb-3">
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        <h3 class="text-primary-custom mb-1">4</h3>
                        <small class="text-muted">Annexes</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center mb-3">
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        <h3 class="text-primary-custom mb-1">24/7</h3>
                        <small class="text-muted">Online Access</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center mb-3">
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        <h3 class="text-primary-custom mb-1">5+</h3>
                        <small class="text-muted">Document Types</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center mb-3">
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        <h3 class="text-primary-custom mb-1">100%</h3>
                        <small class="text-muted">Digital Service</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">Contact Information</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-annex">
                        <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--accent-color);"></i>Manggahan Proper</h4>
                        <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Main Barangay Hall, Manggahan, Pasig City</span></div>
                        <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">+63 908 8653 031</span></div>
                        <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-annex">
                        <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--accent-color);"></i>Karangalan Annex</h4>
                        <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Karangalan Village, Manggahan, Pasig City</span></div>
                        <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">(02) 8123-4568</span></div>
                        <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-annex">
                        <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--accent-color);"></i>Green Park Annex</h4>
                        <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Green Park Subdivision, Manggahan, Pasig City</span></div>
                        <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">(02) 8123-4569</span></div>
                        <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-annex">
                        <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--accent-color);"></i>Napico Annex</h4>
                        <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Napico Area, Manggahan, Pasig City</span></div>
                        <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">(02) 8123-4570</span></div>
                        <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="contact-annex text-center" style="padding-left: 90px;">
                        <h4 class="mb-4">Emergency Contacts</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-3"><div class="contact-info justify-content-start"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Robles St., V. Ignacio Blvrd., East Bank Road, Manggahan Barangay Hall, Pasig City</span></div></div>
                            <div class="col-md-3"><div class="contact-info justify-content-start"><i class="fas fa-phone contact-icon"></i><span class="contact-text">0917 172 9744 / 0908 865 3031</span></div></div>
                            <div class="col-md-3"><div class="contact-info justify-content-start"><i class="fas fa-envelope contact-icon"></i><span class="contact-text">anakngmanggahan01@gmail.com</span></div></div>
                            <div class="col-md-3"><div class="contact-info justify-content-start"><i class="fab fa-facebook contact-icon"></i><span class="contact-text">Anak ng Manggahan</span></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-social">
                        <a href="https://www.facebook.com/profile.php?id=61556545430158" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="mailto:anakngmanggahan01@gmail.com" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="tel:09171729744" title="Phone"><i class="fas fa-phone"></i></a>
                        <a href="https://maps.google.com/?q=Robles+St.+V.+Ignacio+Blvrd.+East+Bank+Road+Manggahan+Barangay+Hall+Pasig+City" title="Location" target="_blank"><i class="fas fa-map-marker-alt"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#announcements">Announcements</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6>Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="register.php">Document Requests</a></li>
                        <li><a href="register.php">Grievance Management</a></li>
                        <li><a href="#announcements">Latest News</a></li>
                        <li><a href="#contact">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6>Contact Info</h6>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Robles St., V. Ignacio Blvrd., East Bank Road, Manggahan Barangay Hall, Pasig City</p>
                    <p><i class="fas fa-phone me-2"></i>0917 172 9744 / 0908 865 3031</p>
                    <p><i class="fas fa-envelope me-2"></i>anakngmanggahan01@gmail.com</p>
                    <p><i class="fas fa-clock me-2"></i>Mon-Fri: 8:00 AM - 5:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6"><p>&copy; 2025 ServeIT - Barangay Manggahan. All rights reserved.</p></div>
                    <div class="col-md-6 text-md-end">
                        <p><a href="#" class="me-3">Privacy Policy</a><a href="#">Terms of Service</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href && href.length > 1) {
              e.preventDefault();
              const target = document.querySelector(href);
              if (target) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight || 0;
                const top = target.getBoundingClientRect().top + window.scrollY - navbarHeight - 10;
                window.scrollTo({ top, behavior: 'smooth' });
              }
            }
          });
        });

        // Auto-dismiss alerts after 5s
        setTimeout(() => {
          document.querySelectorAll('.alert').forEach(el => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
          });
        }, 5000);
      });

      // Navbar background on scroll
      window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;
        if (window.scrollY > 50) {
          navbar.style.background = 'linear-gradient(135deg, rgba(44, 90, 160, 0.95) 0%, rgba(30, 58, 138, 0.95) 100%)';
          navbar.style.backdropFilter = 'blur(10px)';
        } else {
          navbar.style.background = 'linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%)';
          navbar.style.backdropFilter = 'none';
        }
      });

      // Intersection Observer for reveals
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('fade-in'); });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

      document.querySelectorAll('.service-card, .announcement-card, .about-card').forEach(card => observer.observe(card));
    </script>
</body>
</html>
