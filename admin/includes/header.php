<?php
include '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect('../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - E-Voting System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1rem;
        }
        .admin-main {
            flex: 1;
            padding: 2rem;
        }
        .side-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
            margin-bottom: 0.5rem;
        }
        .side-link:hover, .side-link.active {
            background: var(--glass-bg);
            color: var(--accent-color);
        }
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--glass-border);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container nav-container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fas fa-user-shield"></i> E-VOTE ADMIN
                </a>
                <ul class="nav-links">
                    <li><a href="../index.php">View Site</a></li>
                    <li><a href="../logout.php" class="btn btn-glass">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="index.php" class="side-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="elections.php" class="side-link"><i class="fas fa-vote-yea"></i> Manage Elections</a>
            <a href="candidates.php" class="side-link"><i class="fas fa-users"></i> Manage Candidates</a>
            <a href="voters.php" class="side-link"><i class="fas fa-user-friends"></i> Manage Voters</a>
            <a href="results.php" class="side-link"><i class="fas fa-chart-bar"></i> Election Results</a>
        </aside>
        <main class="admin-main">
            <div class="container">
