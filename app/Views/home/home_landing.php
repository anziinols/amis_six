<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agriculture Management Information System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('public/assets/system_images/favicon.ico') ?>" type="image/x-icon">
    <style>
        :root {
            --primary-green: #6ba84f; /* From the logo */
            --light-green: #d2e6c9;
            --navy-blue: #10316b;
            --light-navy: #1c4586;
            --dark-navy: #0a1e40;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--navy-blue);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 60px;
        }

        .navbar-brand span {
            color: white;
            font-weight: 600;
            margin-left: 10px;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-green) !important;
        }

        .nav-link.active {
            color: var(--primary-green) !important;
            border-bottom: 2px solid var(--primary-green);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(16, 49, 107, 0.6), rgba(16, 49, 107, 0.6)), url('<?= base_url('public/assets/images/amis_hero.png') ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--primary-green);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--dark-navy);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Login Form */
        .login-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .login-card h3 {
            color: var(--navy-blue);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-control {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Services Section */
        .services {
            padding: 100px 0;
            background-color: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            color: var(--navy-blue);
            font-weight: 700;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-green);
            transform: translateX(-50%);
        }

        .service-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .service-card i {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        .service-card h4 {
            color: var(--navy-blue);
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* About Section */
        .about {
            padding: 100px 0;
        }

        .about-img {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .about-img img {
            width: 100%;
            height: auto;
            transition: all 0.5s ease;
        }

        .about-img:hover img {
            transform: scale(1.05);
        }

        .about-content h2 {
            color: var(--navy-blue);
            font-weight: 700;
            margin-bottom: 20px;
        }

        .about-content p {
            margin-bottom: 20px;
            line-height: 1.8;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background-color: var(--light-green);
        }

        .feature-card {
            text-align: center;
            margin-bottom: 30px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background-color: var(--navy-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }

        /* Footer */
        footer {
            background-color: var(--navy-blue);
            color: white;
            padding: 50px 0 20px;
        }

        footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
            padding-left: 0;
        }

        footer ul li {
            margin-bottom: 10px;
        }

        footer ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer ul li a:hover {
            color: var(--primary-green);
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--primary-green);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 40px;
        }

        /* Scroll to Top */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .scroll-top.active {
            opacity: 1;
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="<?= base_url('public/assets/system_images/amis_logo.png') ?>" alt="AMIS Logo">
                <span>AMIS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Agriculture Management Information System</h1>
                    <p>A comprehensive government platform integrating NASP, MTDP, and Corporate Plans for strategic agricultural planning, workplan management, activity implementation, performance evaluation, and development coordination across Papua New Guinea.</p>
                    <a href="#about" class="btn btn-primary me-3">Learn More</a>
                    <a href="#services" class="btn btn-outline-light">Our Services</a>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="login-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-dark">Login</h4>
                        </div>
                        <div class="card-body">
                            <?php if (session()->has('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session('error') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->has('success')): ?>
                                <div class="alert alert-success">
                                    <?= session('success') ?>
                                </div>
                            <?php endif; ?>

                            <form id="loginFormMain" action="<?= base_url('login') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" onclick="this.form.submit(); return false;">Login</button>
                            </form>
                            <div class="mt-3 text-center">
                                <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Comprehensive agricultural planning and management solutions for government agencies</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-project-diagram"></i>
                        <h4>Strategic Planning</h4>
                        <p>Manage NASP (National Agriculture Sector Plan), MTDP (Medium Term Development Plan), and Corporate Plans for coordinated agricultural development.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-tasks"></i>
                        <h4>Workplan Management</h4>
                        <p>Create, track, and manage workplans with supervisor assignment, performance periods, and comprehensive activity oversight across branches and departments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>Activity Implementation</h4>
                        <p>Implement and track seven activity types: trainings, infrastructure, inputs, outputs, meetings, agreements, and documents with GPS mapping and detailed monitoring.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-seedling"></i>
                        <h4>Commodity Tracking</h4>
                        <p>Monitor agricultural commodity production, export data, and market information for informed decision-making.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-store"></i>
                        <h4>SME Management</h4>
                        <p>Track and support Small Medium Enterprises in the agricultural sector with comprehensive business profiles and staff management.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-star-half-alt"></i>
                        <h4>Performance Evaluation</h4>
                        <p>Comprehensive activity evaluation and rating system with supervisor oversight, evaluator assessments, and performance tracking across all workplan activities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-chart-bar"></i>
                        <h4>Reporting & Analytics</h4>
                        <p>Generate comprehensive reports on MTDP, NASP, workplans, activities, commodities, HR, and government structure with visual analytics and interactive mapping.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 about-img">
                    <img src="<?= base_url('public/assets/images/agri-women-nursery.jpg') ?>" alt="Agricultural Workers in Nursery" class="img-fluid">
                </div>
                <div class="col-lg-6 about-content ps-lg-5 mt-5 mt-lg-0">
                    <h2>About Agriculture Management Information System</h2>
                    <p>The Agriculture Management Information System (AMIS) is a comprehensive government platform designed to coordinate and manage agricultural development initiatives across Papua New Guinea.</p>
                    <p>Developed by ITU under the EU Funded STREIT Program for the National Department of Agriculture and Livestock, AMIS serves as the central hub for strategic planning, workplan management, and agricultural development coordination. The system integrates NASP (National Agriculture Sector Plan), MTDP (Medium Term Development Plan), and Corporate Plans to ensure aligned agricultural development.</p>
                    <p>Our system provides comprehensive tools including: strategic planning frameworks, workplan and activity management with seven implementation types (trainings, infrastructure, inputs, outputs, meetings, agreements, documents), performance evaluation and rating, supervised activities workflow, duty instructions, proposal tracking, document management, SME support, commodity monitoring, and multi-dimensional reporting—all accessible through a secure, role-based platform with hierarchical government structure integration.</p>
                    <a href="#features" class="btn btn-primary">Explore Features</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>Key Features</h2>
                <p>Powerful tools designed for government agricultural planning and coordination</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h4>Activity Implementation Tracking</h4>
                        <p>Implement and monitor seven activity types: trainings, infrastructure development, agricultural inputs, outputs, meetings, agreements, and document management with detailed tracking.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h4>Supervised Activities Workflow</h4>
                        <p>Supervisor oversight of workplan activities with status tracking, completion marking, output monitoring, and comprehensive activity supervision capabilities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Performance Evaluation System</h4>
                        <p>Comprehensive activity rating and evaluation by designated evaluators with percentage-based scoring, remarks, and performance tracking across all workplan activities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h4>Duty Instructions Management</h4>
                        <p>Create, assign, and track duty instructions with detailed items, responsible officers, timelines, and completion status monitoring for operational efficiency.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <h4>Government Structure Integration</h4>
                        <p>Hierarchical government structure management including provinces, districts, LLGs, and wards with comprehensive geographic coverage and administrative alignment.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h4>GPS Activity Mapping</h4>
                        <p>Interactive map visualization of activities with GPS coordinates, location tracking for infrastructure, training, and input activities with OpenStreetMap integration.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Performance Period Management</h4>
                        <p>Define and manage performance periods for workplan activities with quarterly, annual, or custom timeframes for structured performance tracking and reporting.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Agreement & Meeting Tracking</h4>
                        <p>Comprehensive management of legal agreements, contracts, and meetings with participant tracking, agenda setting, minutes recording, and document attachments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h4>Role-Based Access Control</h4>
                        <p>Secure multi-user system with granular role-based permissions for administrators, supervisors, action officers, evaluators, and commodity board users with hierarchical access.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h5>Agriculture Management Information System</h5>
                    <p>A comprehensive government platform integrating NASP, MTDP, and Corporate Plans for strategic agricultural planning, workplan management, activity implementation, performance evaluation, and development coordination across Papua New Guinea.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#features">Features</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-md-0">
                    <h5>Key Features</h5>
                    <ul>
                        <li><a href="#services">Strategic Planning</a></li>
                        <li><a href="#services">Workplan Management</a></li>
                        <li><a href="#services">Activity Implementation</a></li>
                        <li><a href="#services">Performance Evaluation</a></li>
                        <li><a href="#services">SME Management</a></li>
                        <li><a href="#services">Commodity Tracking</a></li>
                        <li><a href="#features">Supervised Activities</a></li>
                        <li><a href="#features">Duty Instructions</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5>Development Partners</h5>
                    <p class="mb-3">Developed by ITU under the EU Funded STREIT Program for the National Department of Agriculture and Livestock</p>
                    <div class="partner-logos d-flex align-items-center justify-content-start flex-wrap gap-3">
                        <img src="<?= base_url('public/assets/images/itu_logo.png') ?>" alt="ITU Logo" style="height: 50px; width: auto;">
                        <img src="<?= base_url('public/assets/images/funded_eu_logo.png') ?>" alt="EU Funded Logo" style="height: 50px; width: auto;">
                        <img src="<?= base_url('public/assets/images/streit_logo.png') ?>" alt="STREIT Logo" style="height: 50px; width: auto;">
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0"> <?= date('Y') ?> Agriculture Management Information System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <div class="scroll-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Ensure the login form submits normally
            $('#loginFormMain').off('click'); // Remove any click handlers

            // Remove any event handlers that might be interfering with form submission
            $('#loginFormMain button[type="submit"]').off('click');

            // Navbar scroll behavior
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar').css('background-color', '#0a1e40');
                    $('.scroll-top').addClass('active');
                } else {
                    $('.navbar').css('background-color', '#10316b');
                    $('.scroll-top').removeClass('active');
                }
            });

            // Smooth scrolling - modified to be more specific
            // EXCLUDE the login form button by using more specific selectors
            $('a.nav-link, a.navbar-brand, footer a, .hero .btn:not(#loginFormMain .btn)').on('click', function(e) {
                if (this.hash !== '') {
                    e.preventDefault();
                    const hash = this.hash;
                    const target = $(hash);
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 70
                        }, 800);
                    }
                }
            });

            // Scroll top button
            $('.scroll-top').click(function() {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });

            // Active nav link on scroll
            $(window).scroll(function() {
                var scrollDistance = $(window).scrollTop() + 100;

                $('section').each(function(i) {
                    if ($(this).position().top <= scrollDistance) {
                        $('.navbar-nav .nav-link.active').removeClass('active');
                        $('.navbar-nav .nav-link').eq(i).addClass('active');
                    }
                });
            }).scroll();
        });
    </script>
</body>
</html>