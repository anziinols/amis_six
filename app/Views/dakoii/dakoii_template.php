<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dakoii Portal' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('public/assets/system_images/dakoii_logo.png') ?>" type="image/x-icon">
    <style>
        :root {
            --dk-dark-bg: #243147;      /* Lighter navy background */
            --dk-darker-bg: #1e293b;    /* Lighter dark background */
            --dk-sidebar: #2f4055;      /* Lighter sidebar */
            --dk-nav: #1e2a3b;          /* Lighter nav */
            --dk-card: #334155;         /* Lighter card background */
            --dk-hover: #3f506e;        /* Lighter hover state */
            --dk-border: #475569;       /* Lighter border */
            --dk-text: #f0f9ff;         /* Blueish white text */
            --dk-text-muted: #e2e8f0;   /* Lighter muted text with blue tint */
            --dk-link: #93c5fd;         /* Lighter blue link */
            --dk-link-hover: #bfdbfe;   /* Very light blue hover */
            --dk-success: #10b981;      /* Lighter success */
            --dk-warning: #f59e0b;      /* Lighter warning */
            --dk-danger: #ef4444;       /* Lighter danger */
            --dk-card-light: rgba(255, 255, 255, 0.1);  /* Light overlay for cards */
            --dk-input-light: rgba(255, 255, 255, 0.08); /* Light overlay for inputs */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dk-dark-bg);
            color: var(--dk-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            letter-spacing: 0.3px;
        }
        
        /* Enhanced text styles */
        h1, h2, h3, h4, h5, h6 {
            color: var(--dk-text);
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        /* Background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(47, 64, 85, 0.4) 0%, rgba(30, 41, 59, 0.4) 100%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.3) 0%, rgba(63, 80, 110, 0.2) 100%);
            z-index: -1;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background: linear-gradient(180deg, var(--dk-sidebar) 0%, var(--dk-nav) 100%);
            border-right: 1px solid var(--dk-border);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid var(--dk-border);
        }
        
        .sidebar-logo {
            width: 80px;
            margin-bottom: 10px;
        }
        
        /* Enhanced nav link styles */
        .sidebar .nav-link {
            padding: 0.75rem 1rem;
            color: var(--dk-text-muted);
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            transition: all 0.2s ease;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: linear-gradient(145deg, var(--dk-hover), var(--dk-sidebar));
            color: var(--dk-text);
            transform: translateX(5px);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--dk-border);
            text-align: center;
            font-size: 0.8rem;
            color: var(--dk-text-muted);
        }
        
        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* TopNav */
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: linear-gradient(90deg, var(--dk-nav), var(--dk-sidebar));
            border-bottom: 1px solid var(--dk-border);
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .top-nav h4 {
            color: var(--dk-text);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .page-title {
            margin: 0;
            font-weight: 600;
            color: white;
        }
        
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown-toggle {
            background: none;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .user-dropdown-toggle img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-dropdown-menu {
            position: absolute;
            right: 0;
            top: 45px;
            background: linear-gradient(145deg, var(--dk-card), var(--dk-darker-bg));
            border: 1px solid var(--dk-border);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 10px 0;
            min-width: 200px;
            z-index: 1000;
            display: none;
        }
        
        .user-dropdown-menu.show {
            display: block;
        }
        
        .user-dropdown-item {
            padding: 10px 20px;
            display: block;
            color: var(--dk-text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .user-dropdown-item:hover {
            background-color: var(--dk-hover);
            color: var(--dk-text);
        }
        
        .user-dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Enhanced card styles */
        .card {
            background: linear-gradient(145deg, var(--dk-card-light), var(--dk-card));
            border: 1px solid var(--dk-border);
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px -1px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.05), transparent);
            pointer-events: none;
        }
        
        .card-header {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            border-bottom: 1px solid var(--dk-border);
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--dk-text);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .card-body {
            padding: 1.5rem;
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), transparent);
        }
        
        /* Info boxes and stats cards */
        .info-box {
            background: linear-gradient(145deg, var(--dk-card-light), var(--dk-card));
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--dk-border);
            position: relative;
            overflow: hidden;
        }
        
        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.08), transparent);
            pointer-events: none;
        }
        
        .stats-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.05));
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--dk-border);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        /* Enhanced table styles */
        .table {
            color: var(--dk-text);
            background: linear-gradient(145deg, var(--dk-card-light), transparent);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), var(--dk-hover));
            border-bottom: 2px solid var(--dk-border);
            color: var(--dk-text);
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }
        
        .table tbody td {
            border-color: var(--dk-border);
            vertical-align: middle;
            padding: 1rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05), transparent);
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02));
        }
        
        /* Form controls with lighter background */
        .form-control, .form-select {
            background: linear-gradient(145deg, var(--dk-input-light), rgba(255, 255, 255, 0.05));
            border: 1px solid var(--dk-border);
            color: var(--dk-text);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: linear-gradient(145deg, var(--dk-input-light), rgba(255, 255, 255, 0.1));
            border-color: var(--dk-link);
            box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.25);
        }
        
        .form-label {
            color: var(--dk-text-muted);
            margin-bottom: 8px;
        }
        
        /* Enhanced alert styles */
        .alert {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.05));
            border-left: 4px solid transparent;
            backdrop-filter: blur(8px);
            color: var(--dk-text);
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* List groups with lighter background */
        .list-group-item {
            background: linear-gradient(145deg, var(--dk-card-light), transparent);
            border-color: var(--dk-border);
            color: var(--dk-text);
            padding: 1rem 1.25rem;
        }
        
        .list-group-item:hover {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.05));
        }
        
        /* Badges with lighter background */
        .badge {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            border-radius: 2rem;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
        
        .badge-primary {
            background-color: var(--dk-link);
        }
        
        .badge-success {
            background-color: var(--dk-success);
        }
        
        .badge-warning {
            background-color: var(--dk-warning);
            color: #212529;
        }
        
        .badge-danger {
            background-color: var(--dk-danger);
        }
        
        /* Enhanced dropdown styles */
        .user-dropdown-menu a {
            color: var(--dk-text-muted);
            transition: all 0.2s ease;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
        
        .user-dropdown-menu a:hover {
            background: linear-gradient(145deg, var(--dk-hover), var(--dk-sidebar));
            color: var(--dk-text);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex align-items-center mb-4 px-2">
            <img src="<?= base_url('public/assets/system_images/dakoii_logo.png') ?>" alt="Dakoii Logo" height="40">
            <h4 class="ms-2 mb-0">Dakoii Portal</h4>
        </div>
        
        <nav class="nav flex-column">
            <a href="<?= base_url('dakoii/dashboard') ?>" class="nav-link <?= current_url() == base_url('dakoii/dashboard') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="<?= base_url('dakoii/users') ?>" class="nav-link <?= current_url() == base_url('dakoii/users') ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i> Dakoii Users
            </a>
            <a href="<?= base_url('dakoii/administrators') ?>" class="nav-link <?= current_url() == base_url('dakoii/administrators') ? 'active' : '' ?>">
                <i class="fas fa-user-tie"></i> System Administrators
            </a>
            <a href="<?= base_url('dakoii/profile') ?>" class="nav-link <?= current_url() == base_url('dakoii/profile') ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i> My Profile
            </a>
            <a href="<?= base_url('dakoii/logout') ?>" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
        
        <div class="sidebar-footer">
            © <?= date('Y') ?> Department of Agriculture & Livestock
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Nav -->
        <div class="top-nav">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= $title ?? 'Dashboard' ?></h4>
                <div class="user-dropdown">
                    <button class="btn btn-link text-light dropdown-toggle" type="button" onclick="toggleUserDropdown()">
                        <i class="fas fa-user-circle"></i> <?= session()->get('dakoii_name') ?>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdown">
                        <a href="<?= base_url('dakoii/profile') ?>">
                            <i class="fas fa-user-cog"></i> Profile Settings
                        </a>
                        <a href="<?= base_url('dakoii/logout') ?>" class="text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <?= $this->renderSection('content') ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function toggleUserDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                var dropdowns = document.getElementsByClassName("user-dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
        
        // Menu toggle for responsive design
        $('.menu-toggle').click(function() {
            $('.sidebar').toggleClass('show');
        });
        
        // Close sidebar when clicking on a link on small screens
        if ($(window).width() < 992) {
            $('.sidebar .nav-link').click(function() {
                $('.sidebar').removeClass('show');
            });
        }
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 