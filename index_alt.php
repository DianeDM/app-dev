<?php
$hasConfig = is_readable(__DIR__ . '/config/functions.php');
if ($hasConfig) {
    require_once __DIR__ . '/config/functions.php';
}

// Redirect only if MultiRoleSessionManager is available
if (
    $hasConfig &&
    class_exists('MultiRoleSessionManager') &&
    method_exists('MultiRoleSessionManager', 'isRoleLoggedIn') &&
    MultiRoleSessionManager::isRoleLoggedIn('resident')
) {
    header('Location: user/dashboard.php');
    exit();
}

// Success messages
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

$logoPath = 'assets/images/manggahan_logo_no_bg.png';
$logoExists = is_file(__DIR__ . '/' . $logoPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ServeIT - Barangay Manggahan Services</title>
    <meta name="theme-color" content="#0B2A6B" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <style>
        :root{
            --blue-950:#081d4a; --blue-900:#0B2A6B; --blue-800:#103D8C; --blue-700:#1546A0; --blue-600:#1D5ED8; --blue-100:#EAF2FF;
            --gold-700:#9E7715; --gold-600:#B88A1E; --gold-500:#D4AF37; --gold-200:#FFE9A3; --gold-100:#FFF7D6;
            --bg:#ffffff; --bg-muted:#F7FAFF; --text-900:#0F172A; --text-700:#334155; --muted:#64748B; --border:#E6ECF5; --ring:#103D8C;
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
            background-attachment: fixed;
            overflow-x:hidden;
        }

        /* Skip link */
        .skip-link{ position:absolute; left:-999px; top:auto; width:1px; height:1px; overflow:hidden; }
        .skip-link:focus{ position:fixed; left:12px; top:12px; width:auto; height:auto; background:#fff; border:2px solid var(--blue-800); padding:.5rem .75rem; border-radius:10px; z-index:1100; }

        /* Top gold rule */
        .top-rule{ height:6px; background: linear-gradient(90deg, var(--gold-500), var(--gold-700)); }

        /* Navbar */
        .navbar{ background:#fff; border-bottom:1px solid var(--border); padding:.7rem 0; transition: box-shadow .2s ease; }
        .navbar.scrolled{ box-shadow:0 10px 20px rgba(16,61,140,.12); }
        .navbar-brand{ font-weight:800; color:var(--blue-900); letter-spacing:.2px; display:inline-flex; align-items:center; gap:.6rem; }
        .navbar-logo{ width:36px; height:36px; object-fit:contain; }
        .brand-text{ display:inline-flex; align-items:baseline; gap:.35rem; }
        .brand-text strong{ color:var(--blue-900); }
        .brand-text small{ color:var(--muted); font-weight:700; letter-spacing:.2px; }
        .nav-link{ color:var(--text-700); font-weight:700; border-radius:10px; padding:.45rem .75rem; }
        .nav-link:hover,.nav-link:focus{ color:var(--blue-900); background:var(--blue-100); }
        .btn-login{ color:#0b1426; border:0; border-radius:12px; padding:.5rem .9rem; background:linear-gradient(180deg, var(--gold-500), var(--gold-600)); box-shadow:0 10px 22px rgba(212,175,55,.28); }
        .btn-login:hover{ filter:brightness(1.03); transform:translateY(-1px); }
        .navbar-toggler{ border:1px solid var(--border); color:var(--blue-900); border-radius:10px; padding:.35rem .6rem; }

        /* Hero - alternate layout */
        .hero{ position:relative; padding-top:110px; padding-bottom:30px; background:#fff; }
        .hero::before{
            content:""; position:absolute; inset:auto 0 0 0; height:220px;
            background: linear-gradient(180deg, rgba(218,234,255,.6), rgba(234,242,255,1));
            clip-path: polygon(0 0, 100% 35%, 100% 100%, 0 100%);
            z-index: 0;
        }
        .hero .container{ position:relative; z-index:1; }
        .kicker{ display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:var(--blue-900); background:#fff; border:1px solid var(--border); border-left:4px solid var(--gold-500); padding:.35rem .65rem; border-radius:999px; }
        .hero-title{ color:var(--blue-900); font-weight:800; font-size:clamp(2rem,4.4vw,3.1rem); line-height:1.08; letter-spacing:.2px; margin:.7rem 0 .4rem; }
        .underline-gold{ display:inline-block; height:6px; width:120px; background:linear-gradient(90deg, var(--gold-500), var(--gold-700)); border-radius:999px; box-shadow:0 6px 16px rgba(212,175,55,.35); }
        .hero-sub{ color:var(--text-700); font-size:1.06rem; line-height:1.7; margin:1rem 0 1.1rem; }
        .cta-primary{ border:0; color:#fff; border-radius:12px; padding:.7rem 1.1rem; background:linear-gradient(135deg, var(--blue-900), var(--blue-600)); box-shadow:0 12px 28px rgba(16,61,140,.22); }
        .cta-secondary{ color:var(--blue-900); border-radius:12px; padding:.7rem 1.1rem; border:2px solid var(--blue-900); background:#fff; }
        .cta-primary:hover{ transform:translateY(-1px); box-shadow:0 16px 34px rgba(16,61,140,.3); }
        .cta-secondary:hover{ background:var(--blue-100); transform:translateY(-1px); }

        /* Hero stats bar */
        .stats{ display:flex; gap:1rem; flex-wrap:wrap; margin-top:1rem; }
        .stat{
            background:#fff; border:1px solid var(--border); border-top:4px solid var(--gold-500);
            border-radius:16px; padding:12px 14px; min-width:140px; text-align:center; box-shadow:0 6px 18px rgba(16,61,140,.08);
        }
        .stat h4{ color:var(--blue-900); margin:0; font-weight:800; }
        .stat small{ color:var(--muted); font-weight:700; }

        /* Feature tiles */
        .feature-tile{
            position:relative; background:#fff; border:1px solid var(--border); border-radius:16px; padding:18px; height:100%;
            box-shadow:0 6px 18px rgba(16,61,140,.08); transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .feature-tile:hover{ transform:translateY(-6px); box-shadow:0 18px 36px rgba(16,61,140,.16); border-color:#d9c26b; }
        .tile-icon{ width:52px; height:52px; display:grid; place-items:center; border-radius:12px; color:var(--blue-900); background:var(--blue-100); border:1px solid var(--border); margin-bottom:.5rem; }
        .tile-title{ font-weight:800; margin-bottom:.2rem; color:var(--text-900); }
        .tile-desc{ color:var(--muted); margin:0; }

        /* Section */
        .section{ padding:46px 0; }
        .section-head{ display:flex; align-items:center; gap:.6rem; margin-bottom:1rem; }
        .gold-bar{ width:26px; height:4px; background:var(--gold-500); border-radius:999px; }
        .section-title{ color:var(--blue-900); font-weight:800; margin:0; }

        /* Timeline announcements */
        .timeline{ position:relative; padding-left:24px; }
        .timeline::before{ content:""; position:absolute; left:8px; top:0; bottom:0; width:3px; background:linear-gradient(180deg, var(--gold-500), var(--gold-700)); border-radius:999px; }
        .tl-item{ position:relative; margin-bottom:16px; }
        .tl-item::before{ content:""; position:absolute; left:-1px; top:.25rem; width:14px; height:14px; background:#fff; border:3px solid var(--gold-500); border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .announcement{ background:#fff; border:1px solid var(--border); border-radius:16px; padding:16px; box-shadow:0 6px 18px rgba(16,61,140,.08); }
        .ann-date{ display:inline-flex; align-items:center; gap:.45rem; color:var(--blue-900); font-weight:700; background:var(--blue-100); border:1px solid var(--border); padding:.3rem .6rem; border-radius:999px; }

        /* Footer */
        .footer{ background:#fff; border-top:3px solid var(--gold-500); padding:40px 0 18px; margin-top:28px; }
        .footer h6{ color:var(--blue-900); font-weight:800; margin-bottom:.75rem; }
        .footer a{ color:var(--text-700); text-decoration:none; }
        .footer a:hover{ color:var(--blue-900); text-decoration:underline; }
        .social a{ display:inline-grid; place-items:center; width:38px; height:38px; margin-right:.4rem; border-radius:10px; color:#0b1426; background:linear-gradient(180deg, var(--gold-200), var(--gold-500)); border:1px solid #E7C867; box-shadow:0 8px 20px rgba(212,175,55,.32); transition:transform .15s ease, box-shadow .2s ease; }
        .social a:hover{ transform:translateY(-2px); box-shadow:0 12px 26px rgba(212,175,55,.38); }
        .footer-bottom{ margin-top:8px; padding-top:12px; border-top:1px dashed var(--border); color:var(--muted); }

        /* Alert */
        .alert{ border:1px solid #86efac; background:linear-gradient(180deg, #ecfdf5, #f0fdf4); color:#14532d; }

        /* Focus */
        a:focus-visible, button:focus-visible{ outline:3px solid var(--ring); outline-offset:2px; border-radius:10px; }

        /* Reveal */
        .reveal{ opacity:0; transform:translateY(8px); transition:transform .25s ease, opacity .45s ease; }
        .reveal.in{ opacity:1; transform:translateY(0); }

        /* Mobile */
        @media (max-width: 991.98px){ .hero{ padding-top:96px; } }
        @media (max-width: 575.98px){ .nav-link{ margin:.2rem 0; } }
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; transition:none!important; scroll-behavior:auto!important; } }
    </style>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; max-width: 540px;" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="top-rule"></div>
    <nav class="navbar navbar-expand-lg fixed-top" style="top:6px;">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <?php if ($logoExists): ?>
                    <img src="<?php echo htmlspecialchars($logoPath); ?>" alt="Barangay Manggahan Logo" class="navbar-logo" />
                <?php else: ?>
                    <svg class="navbar-logo" viewBox="0 0 48 48" aria-hidden="true">
                        <defs>
                            <linearGradient id="g" x1="0" x2="1" y1="0" y2="1">
                                <stop offset="0%" stop-color="#D4AF37"/>
                                <stop offset="100%" stop-color="#B88A1E"/>
                            </linearGradient>
                        </defs>
                        <circle cx="24" cy="24" r="22" fill="url(#g)"/>
                        <circle cx="24" cy="24" r="16" fill="#fff"/>
                        <path d="M14 28h20v4H14zM18 18h12v8H18z" fill="#0B2A6B"/>
                    </svg>
                <?php endif; ?>
                <span class="brand-text"><strong>ServeIT</strong><small>Barangay Manggahan</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="nav">
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

    <main id="main">
        <!-- Hero -->
        <section id="home" class="hero">
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-6">
                        <span class="kicker"><i class="fas fa-award"></i> Official Barangay Services</span>
                        <h1 class="hero-title">Reliable, Accessible, and Secure Public Services</h1>
                        <div class="underline-gold" aria-hidden="true"></div>
                        <p class="hero-sub">Request documents, file grievances, and stay updated with announcements — all in one platform for Barangay Manggahan.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="register.php" class="btn cta-primary"><i class="fas fa-user-plus me-2"></i>Register Now</a>
                            <a href="#services" class="btn cta-secondary"><i class="fas fa-info-circle me-2"></i>Learn More</a>
                        </div>
                        <div class="stats" role="list">
                            <div class="stat reveal" role="listitem"><h4>4</h4><small>Annexes</small></div>
                            <div class="stat reveal" role="listitem"><h4>24/7</h4><small>Online Access</small></div>
                            <div class="stat reveal" role="listitem"><h4>5+</h4><small>Document Types</small></div>
                            <div class="stat reveal" role="listitem"><h4>100%</h4><small>Digital Service</small></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center reveal">
                            <svg viewBox="0 0 480 320" style="width:100%; max-width:420px" aria-hidden="true">
                                <defs>
                                    <linearGradient id="bg" x1="0" x2="1" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#EAF2FF"/>
                                        <stop offset="100%" stop-color="#ffffff"/>
                                    </linearGradient>
                                    <linearGradient id="r" x1="0" x2="1" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#D4AF37"/>
                                        <stop offset="100%" stop-color="#B88A1E"/>
                                    </linearGradient>
                                </defs>
                                <rect x="0" y="0" width="480" height="320" rx="24" fill="url(#bg)" stroke="#E6ECF5" />
                                <circle cx="380" cy="56" r="10" fill="url(#r)" />
                                <rect x="64" y="80" width="352" height="28" rx="8" fill="#103D8C" opacity=".85" />
                                <rect x="64" y="124" width="240" height="18" rx="8" fill="#1D5ED8" opacity=".65" />
                                <rect x="64" y="154" width="280" height="18" rx="8" fill="#1D5ED8" opacity=".65" />
                                <rect x="64" y="206" width="352" height="64" rx="12" fill="#fff" stroke="#E6ECF5" />
                                <rect x="76" y="220" width="180" height="12" rx="6" fill="#1546A0" opacity=".8" />
                                <rect x="76" y="240" width="260" height="10" rx="5" fill="#1D5ED8" opacity=".6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section id="services" class="section">
            <div class="container">
                <div class="section-head"><div class="gold-bar"></div><h2 class="section-title">Our Services</h2></div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <div class="tile-icon"><i class="fas fa-file-alt"></i></div>
                            <h3 class="tile-title">Document Requests</h3>
                            <p class="tile-desc">Request clearances, certificates, and permits online. Track status and choose pickup location.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <div class="tile-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <h3 class="tile-title">Grievance Management</h3>
                            <p class="tile-desc">File concerns securely, with optional anonymity. Follow resolution progress in-app.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <div class="tile-icon"><i class="fas fa-bullhorn"></i></div>
                            <h3 class="tile-title">Announcements</h3>
                            <p class="tile-desc">Stay updated with news, advisories, and service changes across annexes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements -->
        <section id="announcements" class="section">
            <div class="container">
                <div class="section-head"><div class="gold-bar"></div><h2 class="section-title">Latest Announcements</h2></div>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="timeline">
                            <div class="tl-item reveal">
                                <div class="announcement">
                                    <div class="ann-date"><i class="fas fa-calendar me-1"></i>August 25, 2025</div>
                                    <h4 class="mt-3 mb-2">ServeIT System Now Live!</h4>
                                    <p class="mb-0">We’re excited to launch our new online platform. Residents can request documents and file grievances digitally.</p>
                                </div>
                            </div>
                            <div class="tl-item reveal">
                                <div class="announcement">
                                    <div class="ann-date"><i class="fas fa-calendar me-1"></i>August 24, 2025</div>
                                    <h4 class="mt-3 mb-2">Holiday Schedule Notice</h4>
                                    <p class="mb-0">All barangay offices will be closed on August 30, 2025 (National Heroes Day). Online services remain available.</p>
                                </div>
                            </div>
                            <div class="tl-item reveal">
                                <div class="announcement">
                                    <div class="ann-date"><i class="fas fa-calendar me-1"></i>August 20, 2025</div>
                                    <h4 class="mt-3 mb-2">Document Fee Updates</h4>
                                    <p class="mb-0">Updated fees for business and construction permits are now in effect. See Services for details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="section">
            <div class="container">
                <div class="section-head"><div class="gold-bar"></div><h2 class="section-title">About Barangay Manggahan</h2></div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="feature-tile reveal">
                            <h3 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-home me-2"></i>Our Community</h3>
                            <p>Barangay Manggahan is one of the 30 barangays in Pasig City (District 2), led by Barangay Captain Kap Quin "Kin" Cruz.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="feature-tile reveal">
                            <h3 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-target me-2"></i>Our Mission</h3>
                            <p>We carry out barangay plans and objectives with integrity and collaboration, elevating the standard of public service.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section id="contact" class="section">
            <div class="container">
                <div class="section-head"><div class="gold-bar"></div><h2 class="section-title">Contact Information</h2></div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <h4 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Manggahan Proper</h4>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-map-marker-alt" style="color:var(--blue-900);"></i><span>Main Barangay Hall, Manggahan, Pasig City</span></div>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-phone" style="color:var(--blue-900);"></i><span>+63 908 8653 031</span></div>
                            <div class="d-flex align-items-center gap-2"><i class="fas fa-clock" style="color:var(--blue-900);"></i><span>Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <h4 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Karangalan Annex</h4>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-map-marker-alt" style="color:var(--blue-900);"></i><span>Karangalan Village, Manggahan, Pasig City</span></div>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-phone" style="color:var(--blue-900);"></i><span>(02) 8123-4568</span></div>
                            <div class="d-flex align-items-center gap-2"><i class="fas fa-clock" style="color:var(--blue-900);"></i><span>Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <h4 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Green Park Annex</h4>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-map-marker-alt" style="color:var(--blue-900);"></i><span>Green Park Subdivision, Manggahan, Pasig City</span></div>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-phone" style="color:var(--blue-900);"></i><span>(02) 8123-4569</span></div>
                            <div class="d-flex align-items-center gap-2"><i class="fas fa-clock" style="color:var(--blue-900);"></i><span>Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="feature-tile reveal">
                            <h4 class="mb-3" style="color:var(--blue-900);"><i class="fas fa-building me-2" style="color: var(--gold-500);"></i>Napico Annex</h4>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-map-marker-alt" style="color:var(--blue-900);"></i><span>Napico Area, Manggahan, Pasig City</span></div>
                            <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-phone" style="color:var(--blue-900);"></i><span>(02) 8123-4570</span></div>
                            <div class="d-flex align-items-center gap-2"><i class="fas fa-clock" style="color:var(--blue-900);"></i><span>Mon-Fri: 8:00 AM - 5:00 PM</span></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="feature-tile reveal text-center">
                            <h4 class="mb-4" style="color:var(--blue-900);">Emergency Contacts</h4>
                            <div class="row g-3 justify-content-center">
                                <div class="col-md-3 d-flex align-items-start gap-2"><i class="fas fa-map-marker-alt" style="color:var(--blue-900);"></i><span>Robles St., V. Ignacio Blvrd., East Bank Road, Manggahan Barangay Hall, Pasig City</span></div>
                                <div class="col-md-3 d-flex align-items-start gap-2"><i class="fas fa-phone" style="color:var(--blue-900);"></i><span>0917 172 9744 / 0908 865 3031</span></div>
                                <div class="col-md-3 d-flex align-items-start gap-2"><i class="fas fa-envelope" style="color:var(--blue-900);"></i><span>anakngmanggahan01@gmail.com</span></div>
                                <div class="col-md-3 d-flex align-items-start gap-2"><i class="fab fa-facebook" style="color:var(--blue-900);"></i><span>Anak ng Manggahan</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-4 col-md-6">
                    <div class="social">
                        <a href="https://www.facebook.com/profile.php?id=61556545430158" title="Facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                        <a href="mailto:anakngmanggahan01@gmail.com" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="tel:09171729744" title="Phone"><i class="fas fa-phone"></i></a>
                        <a href="https://maps.google.com/?q=Robles+St.+V.+Ignacio+Blvrd.+East+Bank+Road+Manggahan+Barangay+Hall+Pasig+City" title="Location" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#announcements">Announcements</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6>Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="register.php">Document Requests</a></li>
                        <li><a href="register.php">Grievance Management</a></li>
                        <li><a href="#announcements">Latest News</a></li>
                        <li><a href="#contact">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6>Contact Info</h6>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Robles St., V. Ignacio Blvrd., East Bank Road, Manggahan Barangay Hall, Pasig City</p>
                    <p><i class="fas fa-phone me-2"></i>0917 172 9744 / 0908 865 3031</p>
                    <p><i class="fas fa-envelope me-2"></i>anakngmanggahan01@gmail.com</p>
                    <p><i class="fas fa-clock me-2"></i>Mon-Fri: 8:00 AM - 5:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6"><p class="mb-0">&copy; 2025 ServeIT - Barangay Manggahan. All rights reserved.</p></div>
                    <div class="col-md-6 text-md-end"><p class="mb-0"><a href="#" class="me-3">Privacy Policy</a><a href="#">Terms of Service</a></p></div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(a=>{
            a.addEventListener('click', e=>{
                const target = document.querySelector(a.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                const h = document.querySelector('.navbar').offsetHeight || 64;
                const y = target.getBoundingClientRect().top + window.scrollY - h - 6;
                window.scrollTo({ top: y, behavior: 'smooth' });
            });
        });

        // Navbar shadow
        const navbar = document.querySelector('.navbar');
        const setNav = ()=>{ if (window.scrollY > 10) navbar.classList.add('scrolled'); else navbar.classList.remove('scrolled'); };
        setNav();
        window.addEventListener('scroll', setNav, { passive:true });

        // Reveal on view
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!reduceMotion) {
            const io = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
            }, { threshold:.12, rootMargin:'0px 0px -40px 0px' });
            document.querySelectorAll('.reveal').forEach(el=> io.observe(el));
        } else {
            document.querySelectorAll('.reveal').forEach(el=> el.classList.add('in'));
        }

        // Auto-dismiss alerts
        setTimeout(()=>{
            document.querySelectorAll('.alert').forEach(al=>{ try { new bootstrap.Alert(al).close(); } catch(_){} });
        }, 5000);
    </script>
</body>
</html>
