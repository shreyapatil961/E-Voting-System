<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting System - Secure & Modern</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container nav-container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fas fa-vote-yea"></i> E-VOTE
                </a>
                <div class="menu-toggle" id="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <ul class="nav-links" id="nav-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="elections.php">Elections</a></li>
                    <li><a href="results.php">Results</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="admin-item"><a href="admin/index.php" class="btn btn-admin"><i class="fas fa-shield-halved"></i> Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="dashboard.php" class="btn btn-glass">Dashboard</a></li>
                        <li><a href="logout.php" class="logout-link">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn btn-glass">Login</a></li>
                        <li><a href="register.php" class="btn btn-primary">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
