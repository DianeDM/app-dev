<?php
require_once 'config/functions.php';

// Check if user is logged in and redirect to user dashboard only
if (MultiRoleSessionManager::isRoleLoggedIn('resident')) {
    header('Location: user/dashboard.php');
    exit();
}

// Handle success messages only (no error handling for staff/admin)
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
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <style>
        :root{
            /* Blue-White-Gold palette */
            --blue-950:#081d4a; --blue-900:#0B2A6B; --blue-800:#103D8C; --blue-700:#1546A0; --blue-600:#1D5ED8; --blue-100:#EAF2FF;
            --gold-700:#9E7715; --gold-600:#B88A1E; --gold-500:#D4AF37; --gold-300:#F6E27D; --gold-100:#FFF7D6;
            --text-900:#0F172A; --text-700:#334155; --muted:#64748B;
            --bg:#ffffff; --bg-muted:#F8FAFF; --border:#E6ECF5; --ring:#103D8C;
            --radius:14px;
        }
        html,body{height:100%}
        body{
            font-family:"Public Sans",system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:var(--text-900);
            background:
                radial-gradient(900px 420px at 10% -10%, rgba(16,61,140,.08), transparent 60%),
                radial-gradient(900px 420px at 100% 0%, rgba(212,175,55,.07), transparent 60%),
                linear-gradient(180deg, var(--bg), var(--bg-muted));
            background-attachment:fixed;
            overflow-x:hidden;
        }

        /* Navbar */
        .navbar{ background:#fff; border-bottom:3px solid var(--gold-500); padding:.75rem 0; transition: box-shadow .25s ease, background .25s ease; }
        .navbar.scrolled{ box-shadow:0 10px 28px rgba(16,61,140,.12); }
        .navbar-brand{ color:var(--blue-900); font-weight:800; letter-spacing:.2px; display:inline-flex; align-items:center; gap:.55rem; }
        .navbar-logo{ width:36px; height:36px; object-fit:contain; }
        .nav-link{ position:relative; color:var(--text-700); font-weight:700; border-radius:10px; padding:.45rem .75rem; transition:color .2s ease, background .2s ease; }
        .nav-link:hover,.nav-link:focus{ color:var(--blue-900); background:var(--blue-100); }
        .nav-link::after{ content:""; position:absolute; left:.7rem; right:.7rem; bottom:.25rem; height:2px; background:linear-gradient(90deg, var(--gold-500), var(--blue-600)); transform:scaleX(0); transform-origin:left; transition:transform .25s ease; }
        .nav-link:hover::after{ transform:scaleX(1); }
        .btn-login{
            position:relative; color:#0b1426; border:none; border-radius:12px; padding:.5rem .9rem;
            background:linear-gradient(180deg, var(--gold-500), var(--gold-600)); box-shadow:0 10px 22px rgba(212,175,55,.28);
            transition:transform .15s ease, filter .2s ease, box-shadow .2s ease;
        }
        .btn-login::after{ content:""; position:absolute; inset:-2px; border-radius:14px; padding:2px; background:linear-gradient(135deg, rgba(212,175,55,.55), rgba(29,94,216,.55)); -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; opacity:.0; transition:opacity .2s ease; }
        .btn-login:hover{ transform:translateY(-1px); filter:brightness(1.02); box-shadow:0 14px 28px rgba(212,175,55,.35); }
        .btn-login:hover::after{ opacity:.6; }
        .navbar-toggler{ border:1px solid var(--border); border-radius:10px; padding:.35rem .6rem; color:var(--blue-900); }

        /* Hero */
        .hero-section{ padding-top:112px; padding-bottom:38px; background:linear-gradient(180deg,#fff, #f9fbff 60%, #f7faff); }
        .kicker{ display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:var(--blue-900); background:#fff; border:1px solid var(--border); border-left:4px solid var(--gold-500); padding:.35rem .65rem; border-radius:999px; }
        .hero-title{ color:var(--blue-900); font-weight:800; font-size:clamp(2rem,4.5vw,3.2rem); line-height:1.08; letter-spacing:.2px; margin:.7rem 0 .4rem; }
        .hero-subtitle{ color:var(--text-700); font-size:1.05rem; line-height:1.7; margin-bottom:1.1rem; }
        .btn-cta{ position:relative; border:0; color:#fff; border-radius:12px; padding:.7rem 1.1rem; background:linear-gradient(135deg, var(--blue-900) 0%, var(--blue-600) 100%); box-shadow:0 12px 28px rgba(16,61,140,.22); transition:transform .15s ease, box-shadow .2s ease; }
        .btn-cta::before{ content:""; position:absolute; top:0; left:-30%; width:30%; height:100%; background:linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent); transform:skewX(-20deg); opacity:0; transition:opacity .2s ease, left .6s ease; }
        .btn-cta:hover{ transform:translateY(-1px); box-shadow:0 16px 34px rgba(16,61,140,.3); }
        .btn-cta:hover::before{ opacity:1; left:130%; }
        .btn-outline-light{ color:var(--blue-900); border:2px solid var(--blue-900); border-radius:12px; padding:.7rem 1.1rem; background:#fff; transition:background .2s ease, color .2s ease, transform .15s ease; }
        .btn-outline-light:hover{ background:var(--blue-100); transform:translateY(-1px); }

        /* Hero visual */
        .hero-visual{ position:relative; min-height:300px; }
        .hero-icon-container{ position:relative; width:360px; height:320px; margin:0 auto; border-radius:18px; background:#fff; border:1px solid var(--border); box-shadow:0 14px 28px rgba(16,61,140,.12); display:grid; place-items:center; overflow:hidden; }
        .hero-icon-container::after{ content:""; position:absolute; inset:0; background: linear-gradient(90deg, rgba(212,175,55,.08), transparent 40%, transparent 60%, rgba(29,94,216,.08)); pointer-events:none; }
        .hero-main-icon{ font-size:72px; color:var(--blue-800); }
        .floating-elements .floating-icon{ position:absolute; width:62px; height:62px; display:grid; place-items:center; border-radius:14px; color:var(--blue-900); background:var(--blue-100); border:1px solid var(--border); animation:float 6.5s ease-in-out infinite; box-shadow:0 10px 20px rgba(16,61,140,.1); }
        .floating-icon-1{ top:10%; left:9%; animation-delay:.2s; }
        .floating-icon-2{ bottom:12%; right:10%; animation-delay:.6s; }
        .floating-icon-3{ top:16%; right:6%; animation-delay:.9s; }
        .floating-icon-4{ bottom:10%; left:14%; animation-delay:1.2s; }
        @keyframes float{ 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }

        /* Sections */
        .services-section,.announcements-section,.about-section,.contact-section{ padding:48px 0; }
        .section-title{ color:var(--blue-900); font-weight:800; letter-spacing:.2px; margin-bottom:.85rem; display:flex; align-items:center; gap:.6rem; }
        .section-title::before{ content:""; width:26px; height:4px; border-radius:999px; background:var(--gold-500); }
        .text-primary-custom{ color:var(--blue-900)!important; }

        /* Cards */
        .service-card,.announcement-card,.about-card,.contact-annex{ position:relative; background:#fff; border:1px solid var(--border); border-top:4px solid var(--gold-500); border-radius:16px; padding:18px; height:100%; box-shadow:0 6px 18px rgba(16,61,140,.08); transform:translateY(8px); opacity:0; transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease, opacity .45s ease; }
        .service-card.fade-in,.announcement-card.fade-in,.about-card.fade-in,.contact-annex.fade-in{ transform:translateY(0); opacity:1; }
        .service-card::after,.announcement-card::after,.about-card::after,.contact-annex::after{ content:""; position:absolute; inset:0; border-radius:16px; pointer-events:none; background:linear-gradient(135deg, rgba(212,175,55,.08), rgba(29,94,216,.08)); opacity:0; transition:opacity .25s ease; }
        .service-card:hover,.announcement-card:hover,.about-card:hover,.contact-annex:hover{ transform:translateY(-6px); box-shadow:0 18px 36px rgba(16,61,140,.16); border-color:#d9c26b; }
        .service-card:hover::after,.announcement-card:hover::after,.about-card:hover::after,.contact-annex:hover::after{ opacity:.8; }
        .service-icon{ width:52px; height:52px; display:grid; place-items:center; border-radius:12px; color:var(--blue-900); background:var(--blue-100); border:1px solid var(--border); margin-bottom:.5rem; }
        .service-title{ font-weight:800; margin-bottom:.25rem; color:var(--text-900); }
        .service-description{ color:var(--muted); margin:0; }

        /* Announcement date pill */
        .announcement-date{ display:inline-flex; align-items:center; gap:.45rem; color:var(--blue-900); font-weight:700; background:var(--blue-100); border:1px solid var(--border); padding:.3rem .6rem; border-radius:999px; }

        /* Contact */
        .contact-info{ display:flex; align-items:center; gap:.6rem; color:var(--text-700); margin:.35rem 0; }
        .contact-icon{ color:var(--blue-900); width:18px; text-align:center; }

        /* Footer */
        .footer{ margin-top:30px; padding:42px 0 22px; background:#fff; border-top:3px solid var(--gold-500); }
        .footer h6{ color:var(--blue-900); font-weight:800; margin-bottom:.75rem; }
        .footer a{ color:var(--text-700); text-decoration:none; }
        .footer a:hover{ color:var(--blue-900); text-decoration:underline; }
        .footer-social a{ display:inline-grid; place-items:center; width:38px; height:38px; margin-right:.4rem; border-radius:10px; color:#0b1426; background:linear-gradient(180deg, #FFE9A3, #F6D567); border:1px solid #E7C867; box-shadow:0 8px 20px rgba(212,175,55,.32); transition:transform .15s ease, box-shadow .2s ease; }
        .footer-social a:hover{ transform:translateY(-2px); box-shadow:0 12px 26px rgba(212,175,55,.38); }
        .footer-bottom{ margin-top:8px; padding-top:12px; border-top:1px dashed var(--border); color:var(--muted); }

        /* Alerts */
        .alert{ border:1px solid #86efac; background:linear-gradient(180deg, #ecfdf5, #f0fdf4); color:#14532d; }

        /* Focus */
        a:focus-visible,button:focus-visible{ outline:3px solid var(--ring); outline-offset:2px; border-radius:10px; }

        /* Mobile */
        @media (max-width: 991.98px){ .hero-section{ padding-top:100px; } .hero-icon-container{ width:100%; max-width:420px; height:280px; } }
        @media (max-width: 575.98px){ .nav-link{ margin:.2rem 0; } .hero-section{ padding-top:92px; } }

        /* Motion */
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; transition:none!important; scroll-behavior:auto!important; } }
    </style>
</head>
<body>
<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; max-width: 520px;" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#home">
            <img src="assets/images/manggahan_logo_no_bg.png" alt="Barangay Manggahan Logo" class="navbar-logo me-2">ServeIT
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#announcements">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="login.php" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <span class="kicker"><i class="fas fa-award"></i> Official Barangay Services</span>
                <h1 class="hero-title">Welcome to ServeIT</h1>
                <p class="hero-subtitle">
                    Your digital gateway to Barangay Manggahan services. Request documents, file grievances, and stay updated with barangay announcements — all in one convenient platform.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="register.php" class="btn btn-cta me-2">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                    </a>
                    <a href="#services" class="btn btn-outline-light">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual text-center">
                    <div class="hero-icon-container">
                        <i class="fas fa-city hero-main-icon" aria-hidden="true"></i>
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

<!-- Services Section -->
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

<!-- Announcements Section -->
<section id="announcements" class="announcements-section">
    <div class="container">
        <h2 class="section-title text-primary-custom">Latest Announcements</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="announcement-card mb-3">
                    <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 25, 2025</div>
                    <h4 class="mt-3">ServeIT System Now Live!</h4>
                    <p class="mb-0">We are excited to announce the launch of our new online service platform. Residents can now request documents and file grievances digitally.</p>
                </div>
                <div class="announcement-card mb-3">
                    <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 24, 2025</div>
                    <h4 class="mt-3">Holiday Schedule Notice</h4>
                    <p class="mb-0">Please be advised that all barangay offices will be closed on August 30, 2025 (National Heroes Day). Online services will remain available.</p>
                </div>
                <div class="announcement-card">
                    <div class="announcement-date"><i class="fas fa-calendar me-1"></i>August 20, 2025</div>
                    <h4 class="mt-3">Document Fee Updates</h4>
                    <p class="mb-0">Updated fees for business permits and construction permits are now in effect. Please check our services section for current rates.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
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
                <div class="bg-white p-3 rounded-3 shadow-sm border" style="border-color:var(--border)!important;">
                    <h3 class="text-primary-custom mb-1">4</h3>
                    <small class="text-muted">Annexes</small>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center mb-3">
                <div class="bg-white p-3 rounded-3 shadow-sm border" style="border-color:var(--border)!important;">
                    <h3 class="text-primary-custom mb-1">24/7</h3>
                    <small class="text-muted">Online Access</small>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center mb-3">
                <div class="bg-white p-3 rounded-3 shadow-sm border" style="border-color:var(--border)!important;">
                    <h3 class="text-primary-custom mb-1">5+</h3>
                    <small class="text-muted">Document Types</small>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center mb-3">
                <div class="bg-white p-3 rounded-3 shadow-sm border" style="border-color:var(--border)!important;">
                    <h3 class="text-primary-custom mb-1">100%</h3>
                    <small class="text-muted">Digital Service</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title">Contact Information</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-annex">
                    <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Manggahan Proper</h4>
                    <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Main Barangay Hall, Manggahan, Pasig City</span></div>
                    <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">+63 908 8653 031</span></div>
                    <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-annex">
                    <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Karangalan Annex</h4>
                    <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Karangalan Village, Manggahan, Pasig City</span></div>
                    <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">(02) 8123-4568</span></div>
                    <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-annex">
                    <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Green Park Annex</h4>
                    <div class="contact-info"><i class="fas fa-map-marker-alt contact-icon"></i><span class="contact-text">Green Park Subdivision, Manggahan, Pasig City</span></div>
                    <div class="contact-info"><i class="fas fa-phone contact-icon"></i><span class="contact-text">(02) 8123-4569</span></div>
                    <div class="contact-info"><i class="fas fa-clock contact-icon"></i><span class="contact-text">Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-annex">
                    <h4 class="mb-3"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Napico Annex</h4>
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
                        <div class="col-md-3">
                            <div class="contact-info justify-content-start">
                                <i class="fas fa-map-marker-alt contact-icon"></i>
                                <span class="contact-text">Robles St., V. Ignacio Blvrd., East Bank Road, Manggahan Barangay Hall, Pasig City</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="contact-info justify-content-start">
                                <i class="fas fa-phone contact-icon"></i>
                                <span class="contact-text">0917 172 9744 / 0908 865 3031</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="contact-info justify-content-start">
                                <i class="fas fa-envelope contact-icon"></i>
                                <span class="contact-text">anakngmanggahan01@gmail.com</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="contact-info justify-content-start">
                                <i class="fab fa-facebook contact-icon"></i>
                                <span class="contact-text">Anak ng Manggahan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=61556545430158" title="Facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                    <a href="mailto:anakngmanggahan01@gmail.com" title="Email"><i class="fas fa-envelope"></i></a>
                    <a href="tel:09171729744" title="Phone"><i class="fas fa-phone"></i></a>
                    <a href="https://maps.google.com/?q=Robles+St.+V.+Ignacio+Blvrd.+East+Bank+Road+Manggahan+Barangay+Hall+Pasig+City" title="Location" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt"></i></a>
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
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 ServeIT - Barangay Manggahan. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <a href="#" class="me-3">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            const navbarHeight = document.querySelector('.navbar').offsetHeight || 64;
            const targetPosition = target.getBoundingClientRect().top + window.scrollY - navbarHeight - 6;
            window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        });
    });

    // Navbar shadow on scroll
    const navbar = document.querySelector('.navbar');
    const setNavScrolled = () => { if (window.scrollY > 50) navbar.classList.add('scrolled'); else navbar.classList.remove('scrolled'); };
    setNavScrolled();
    window.addEventListener('scroll', setNavScrolled, { passive: true });

    // Reveal animations
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReduced) {
        const observer = new IntersectionObserver((entries)=>{
            entries.forEach(entry=>{ if(entry.isIntersecting){ entry.target.classList.add('fade-in'); observer.unobserve(entry.target); } });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.service-card, .announcement-card, .about-card, .contact-annex').forEach(el=> observer.observe(el));
    } else {
        document.querySelectorAll('.service-card, .announcement-card, .about-card, .contact-annex').forEach(el=> el.classList.add('fade-in'));
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function(){
        document.querySelectorAll('.alert').forEach(function(alert){ try { new bootstrap.Alert(alert).close(); } catch(e) {} });
    }, 5000);

    // iOS Safari viewport + touch fixes
    (function(){
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        if(!isIOS) return;
        function setViewportHeight(){ const vh = window.innerHeight * 0.01; document.documentElement.style.setProperty('--vh', `${vh}px`); }
        setViewportHeight();
        window.addEventListener('resize', setViewportHeight);
        window.addEventListener('orientationchange', setViewportHeight);
        document.addEventListener('touchstart', function(){}, { passive: true });
        document.addEventListener('touchmove', function(){}, { passive: true });
        document.body.style.overscrollBehavior = 'contain';
    })();
</script>
</body>
</html>
