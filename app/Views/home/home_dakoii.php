<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dakoii Portal - Agriculture Management Information System</title>
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
            --primary-green: #6ba84f;
            --dark-navy: #071330;
            --navy-blue: #0a1e40;
            --light-navy: #162955;
            --accent-color: #4e9af1;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--dark-navy) 0%, var(--navy-blue) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(10, 30, 64, 0.6) 0%, rgba(7, 19, 48, 0.6) 100%),
                linear-gradient(135deg, rgba(7, 19, 48, 0.4) 0%, rgba(22, 41, 85, 0.3) 100%);
            z-index: -1;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            background: rgba(8, 22, 50, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .login-logo {
            max-width: 140px;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.1));
        }
        
        .login-title {
            color: white;
            font-weight: 600;
            font-size: 2rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .form-control {
            background: rgba(7, 19, 48, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            background: rgba(10, 30, 64, 0.8);
            border-color: var(--accent-color);
            box-shadow: 0 0 15px rgba(78, 154, 241, 0.2);
            color: white;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input-group .form-control {
            padding-left: 45px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, #3b7fd6 100%);
            border: none;
            padding: 15px;
            font-weight: 500;
            font-size: 1rem;
            border-radius: 12px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #3b7fd6 0%, #2d6bc4 100%);
            box-shadow: 0 5px 15px rgba(59, 127, 214, 0.3);
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ff6b6b;
            border-left: 4px solid var(--danger-color);
        }
        
        .back-to-home {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            margin-top: 30px;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .back-to-home:hover {
            color: var(--accent-color);
            transform: translateX(-5px);
        }
        
        .back-to-home i {
            margin-right: 8px;
        }
        
        .login-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="<?= base_url('public/assets/system_images/dakoii_logo.png') ?>" alt="Dakoii Portal" class="login-logo">
            <h1 class="login-title">Dakoii Portal</h1>
            <p class="login-subtitle">Agriculture Management Information System</p>
        </div>
        
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        
        <form id="dakoiiLoginForm" action="<?= base_url('dakoii/login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" placeholder="Username" name="username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="<?= base_url() ?>" class="back-to-home">
                <i class="fas fa-arrow-left"></i> Back to AMIS Home
            </a>
        </div>
        
        <div class="login-footer">
            © <?= date('Y') ?> Department of Agriculture and Livestock. All rights reserved.
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Simple form validation
        $('#dakoiiLoginForm').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Logging in...');
        });
    });
    </script>
</body>
</html> 