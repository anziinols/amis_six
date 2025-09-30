<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'AMIS System' ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('public/assets/system_images/favicon.ico') ?>" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom Styles -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-green: #6ba84f;
            --light-green: #8bc34a;
            --dark-green: #558b2f;
            --navy-blue: #1a237e;
            --light-navy: #283593;
            --dark-navy: #0d47a1;
            --accent-color: var(--primary-green);
            --success-color: #43a047;
            --warning-color: #fdd835;
            --danger-color: #e53935;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
            --transition-speed: 0.3s;
            --light-bg: #f5f7fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
            color: #2c3e50;
            transition: margin var(--transition-speed) ease;
        }

        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(180deg, var(--navy-blue) 0%, var(--dark-navy) 100%);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            z-index: 100;
            transition: width var(--transition-speed) ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.1);
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .toggle-sidebar {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8) !important;
            padding: 0.8rem 1rem;
            margin: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white !important;
            background-color: rgba(107, 168, 79, 0.2);
        }

        .sidebar .nav-link.active {
            color: white !important;
            background-color: var(--primary-green);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .sidebar .nav-link i {
            margin-right: 0.8rem;
            width: 1.5rem;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        .sidebar.collapsed .sidebar-logo {
            display: none;
        }

        .nav-item-divider {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 0.5rem 0.5rem;
        }

        /* Main content styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left var(--transition-speed) ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Top navigation */
        .top-navbar {
            background-color: white;
            box-shadow: var(--card-shadow);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .user-menu img {
            height: 36px;
            width: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        /* Cards and other components */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--navy-blue);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Responsive styles */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.expanded {
                width: var(--sidebar-width);
                box-shadow: 0 0 15px rgba(0,0,0,0.2);
            }

            .sidebar.expanded .nav-text {
                display: inline !important;
            }

            .sidebar.expanded .sidebar-title {
                display: block !important;
            }

            .sidebar.expanded .sidebar-logo {
                display: block !important;
            }

            .mobile-nav-toggle {
                display: block !important;
            }
        }

        @media (max-width: 767.98px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.expanded {
                width: var(--sidebar-width);
            }

            .mobile-nav-toggle {
                display: block !important;
            }
        }

        /* Desktop sidebar collapse behavior */
        @media (min-width: 992px) {
            .sidebar.collapsed {
                width: 0;
                overflow: hidden;
            }

            .sidebar.collapsed + .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Utility classes */
        .mobile-nav-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            border: none;
            background-color: var(--primary-green);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* Button styles */
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }

        /* Notification badge */
        .badge.bg-danger {
            background-color: var(--danger-color) !important;
        }

        /* Dropdown styles */
        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 0.5rem;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background-color: var(--primary-green);
        }
    </style>
    <?= $this->renderSection('head') ?>
</head>
<body>
    <!-- Mobile navigation toggle button (only visible on small screens) -->
    <button class="mobile-nav-toggle" id="mobileNavToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('public/assets/system_images/amis_logo.png') ?>" alt="AMIS Logo" height="40" class="sidebar-logo">
            <h4 class="sidebar-title">AMIS</h4>
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="p-2 mt-2">
            <ul class="nav flex-column">
                <?php
                $userRole = session()->get('role');
                $allowedMenus = getNavigationMenus($userRole);
                ?>

                <!-- Dashboard - Available to all roles -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Admin Panel Menu - Only for Admin -->
                <?php if (canAccessMenu('admin_panel', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'admin') !== false ? 'active' : '' ?>" href="#adminSubmenu" data-bs-toggle="collapse">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Admin Panel</span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse <?= strpos(current_url(), 'admin') !== false ? 'show' : '' ?>" id="adminSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/users') !== false ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>">
                                    <i class="fas fa-users"></i>
                                    <span class="nav-text">Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/regions') !== false ? 'active' : '' ?>" href="<?= base_url('admin/regions') ?>">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="nav-text">Regions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/gov-structure') !== false ? 'active' : '' ?>" href="<?= base_url('admin/gov-structure') ?>">
                                    <i class="fas fa-sitemap"></i>
                                    <span class="nav-text">Gov. Structure</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/branches') !== false ? 'active' : '' ?>" href="<?= base_url('admin/branches') ?>">
                                    <i class="fas fa-building"></i>
                                    <span class="nav-text">Branches</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/mtdp-plans') !== false ? 'active' : '' ?>" href="<?= base_url('admin/mtdp-plans') ?>">
                                    <i class="fas fa-project-diagram"></i>
                                    <span class="nav-text">MTDP Plans</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos(current_url(), 'admin/nasp-plans') !== false || strpos(current_url(), 'admin/nasp-kras') !== false || strpos(current_url(), 'admin/nasp-objectives') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/nasp-plans') ?>">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="nav-text">NASP Plans</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/corporate-plans') !== false ? 'active' : '' ?>" href="<?= base_url('admin/corporate-plans') ?>">
                                    <i class="fas fa-briefcase"></i>
                                    <span class="nav-text">Corporate Plans</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/org-settings') !== false ? 'active' : '' ?>" href="<?= base_url('admin/org-settings') ?>">
                                    <i class="fas fa-cogs"></i>
                                    <span class="nav-text">Org.Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'admin/commodities') !== false ? 'active' : '' ?>" href="<?= base_url('admin/commodities') ?>">
                                    <i class="fas fa-seedling"></i>
                                    <span class="nav-text">Commodities</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- SMEs Menu - Only for Admin -->
                <?php if (canAccessMenu('smes', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'smes') !== false ? 'active' : '' ?>" href="<?= base_url('smes') ?>">
                        <i class="fas fa-store"></i>
                        <span class="nav-text">SMEs</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Commodity Boards Menu - For Admin and Commodity Users -->
                <?php if (canAccessMenu('commodity_boards', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'commodity-boards') !== false ? 'active' : '' ?>" href="<?= base_url('commodity-boards') ?>">
                        <i class="fas fa-boxes"></i>
                        <span class="nav-text">Commodity Boards</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Workplans Menu - For Admin and Supervisor -->
                <?php if (canAccessMenu('workplans', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'workplans') !== false ? 'active' : '' ?>" href="<?= base_url('workplans') ?>">
                        <i class="fas fa-tasks"></i>
                        <span class="nav-text">Workplans</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Supervised Activities Menu - For Supervisors -->
                <?php if (canAccessMenu('supervised_activities', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'supervised-activities') !== false ? 'active' : '' ?>" href="<?= base_url('supervised-activities') ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">Supervised Activities</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Evaluation Menu - For Admin and Supervisor -->
                <?php if (canAccessMenu('evaluation', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'evaluation') !== false ? 'active' : '' ?>" href="<?= base_url('evaluation') ?>">
                        <i class="fas fa-clipboard-check"></i>
                        <span class="nav-text">Evaluation</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Duty Instructions Menu - For Admin and Supervisor -->
                <?php if (canAccessMenu('duty_instructions', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(current_url(), 'duty-instructions') !== false ? 'active' : '' ?>" href="<?= base_url('duty-instructions') ?>">
                        <i class="fas fa-tasks"></i>
                        <span class="nav-text">Duty Instructions</span>
                    </a>
                </li>
                <?php endif; ?>


                <!-- My Activities Menu - For Admin and Supervisor -->
                <?php if (canAccessMenu('my_activities', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == base_url('activities') || strpos(current_url(), base_url('activities/')) !== false) ? 'active' : '' ?>" href="<?= base_url('activities') ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">My Activities</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Reports Menu - For All Roles -->
                <?php if (canAccessMenu('reports', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'reports/mtdp') !== false || strpos(current_url(), 'reports/nasp') !== false || strpos(current_url(), 'reports/workplan') !== false || strpos(current_url(), 'reports/activities-map') !== false || strpos(current_url(), 'reports/commodity') !== false || strpos(current_url(), 'reports/hr') !== false) ? 'active' : '' ?>" href="#reportsSubmenu" data-bs-toggle="collapse">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse <?= (strpos(current_url(), 'reports/mtdp') !== false || strpos(current_url(), 'reports/nasp') !== false || strpos(current_url(), 'reports/workplan') !== false || strpos(current_url(), 'reports/activities-map') !== false || strpos(current_url(), 'reports/commodity') !== false || strpos(current_url(), 'reports/hr') !== false) ? 'show' : '' ?>" id="reportsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/mtdp') !== false ? 'active' : '' ?>" href="<?= base_url('reports/mtdp') ?>">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="nav-text">MTDP Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/nasp') !== false ? 'active' : '' ?>" href="<?= base_url('reports/nasp') ?>">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="nav-text">NASP Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/workplan') !== false ? 'active' : '' ?>" href="<?= base_url('reports/workplan') ?>">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="nav-text">Workplan Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/activities-map') !== false ? 'active' : '' ?>" href="<?= base_url('reports/activities-map') ?>">
                                    <i class="fas fa-map-marked-alt"></i>
                                    <span class="nav-text">Activities Map</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/commodity') !== false ? 'active' : '' ?>" href="<?= base_url('reports/commodity') ?>">
                                    <i class="fas fa-seedling"></i>
                                    <span class="nav-text">Commodity Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), 'reports/hr') !== false ? 'active' : '' ?>" href="<?= base_url('reports/hr') ?>">
                                    <i class="fas fa-users"></i>
                                    <span class="nav-text">HR Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Profile Menu - For All Roles -->
                <?php if (canAccessMenu('profile', $userRole)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('dashboard/profile') ? 'active' : '' ?>" href="<?= base_url('dashboard/profile') ?>">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </li>
                <?php endif; ?>



                <div class="nav-item-divider"></div>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?? 'Dashboard' ?></h5>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <a href="#" class="text-dark position-relative">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                </div>

                <div class="dropdown user-menu">
                    <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="me-2 d-none d-sm-block">
                            <div class="fw-bold"><?= session()->get('user_name') ?></div>
                            <div class="small text-muted"><?= ucfirst(session()->get('role')) ?></div>
                        </div>
                        <?php
                        $userImage = session()->get('id_photo');
                        if ($userImage && file_exists(ROOTPATH . $userImage)) {
                            $imageUrl = base_url($userImage);
                        } else {
                            $imageUrl = base_url('public/assets/system_images/no-img.jpg');
                        }
                        ?>
                        <img src="<?= $imageUrl ?>" alt="User Avatar" class="rounded-circle" width="36" height="36">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('dashboard/profile') ?>"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Additional JS libraries can be added here -->
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- HTML2PDF Library for PDF Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- AMIS PDF Generator -->
    <script src="<?= base_url('public/assets/js/pdf-generator.js') ?>"></script>



    <!-- Global JavaScript Variables -->
    <script>
        // Define global base URL for JavaScript files
        window.AMIS_BASE_URL = '<?= base_url() ?>';
    </script>

    <!-- Initialize Toastr settings -->
    <script>
        // Configure Toastr notification settings
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 15000,  // Increase to 15 seconds
            extendedTimeOut: 5000,  // Increase to 5 seconds
            preventDuplicates: true,
            showDuration: "500",
            hideDuration: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            newestOnTop: true
        };

        // Display flash messages if any
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')): ?>
            toastr.warning('<?= session()->getFlashdata('warning') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            toastr.info('<?= session()->getFlashdata('info') ?>');
        <?php endif; ?>
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            // Function to check screen size and apply default sidebar state
            function checkScreenSize() {
                if ($(window).width() < 992) {
                    // Mobile/tablet view - sidebar hidden by default
                    $('#sidebar').removeClass('expanded').addClass('collapsed');
                    $('#mainContent').addClass('expanded');
                    $('#toggleSidebar i').removeClass('fa-chevron-left').addClass('fa-bars');
                }
            }

            // Run once on document ready
            checkScreenSize();

            // Run on window resize
            $(window).resize(function() {
                checkScreenSize();
            });

            // Toggle sidebar on desktop
            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');

                // Toggle icon
                if ($('#sidebar').hasClass('collapsed')) {
                    $('#toggleSidebar i').removeClass('fa-chevron-left').addClass('fa-bars');
                } else {
                    $('#toggleSidebar i').removeClass('fa-bars').addClass('fa-chevron-left');
                }
            });

            // Mobile menu toggle
            $('#mobileNavToggle').click(function() {
                $('#sidebar').toggleClass('expanded');
            });

            // Close mobile sidebar when clicking outside
            $(document).on('click touchstart', function(e) {
                // Only apply this on mobile screens
                if ($(window).width() >= 992) return;

                // If the click is outside the sidebar and the mobile toggle button
                if (!$(e.target).closest('#sidebar').length &&
                    !$(e.target).closest('#mobileNavToggle').length &&
                    !$(e.target).closest('#toggleSidebar').length) {
                    $('#sidebar').removeClass('expanded');
                }
            });
        });
    </script>

    <!-- Custom JS -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
