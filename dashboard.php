<?php
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user stats
$stmt = $conn->prepare("SELECT COUNT(*) FROM votes WHERE user_id = ?");
$stmt->execute([$user_id]);
$votes_cast = $stmt->fetchColumn();

// Fetch active elections
$stmt = $conn->prepare("SELECT * FROM elections WHERE status = 'active' ORDER BY end_date ASC");
$stmt->execute();
$active_elections = $stmt->fetchAll();

// Fetch election history for this user
$stmt = $conn->prepare("SELECT election_id FROM votes WHERE user_id = ?");
$stmt->execute([$user_id]);
$voted_election_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="dashboard-header" style="padding: 2rem 0;">
    <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.8rem;">Welcome, <span style="color: var(--accent-color);"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>!</h2>
            <p style="opacity: 0.8;">Voter ID: #<?php echo str_pad($user_id, 5, '0', STR_PAD_LEFT); ?></p>
        </div>
        <div style="display: flex; gap: 2rem;">
            <div style="text-align: center;">
                <h3 style="font-size: 1.5rem; color: var(--accent-color);"><?php echo $votes_cast; ?></h3>
                <p style="font-size: 0.9rem; opacity: 0.7;">Votes Cast</p>
            </div>
            <div style="text-align: center;">
                <h3 style="font-size: 1.5rem; color: var(--accent-color);"><?php echo count($active_elections); ?></h3>
                <p style="font-size: 0.9rem; opacity: 0.7;">Active Elections</p>
            </div>
        </div>
    </div>
</div>

<section class="elections-section" style="padding: 2rem 0;">
    <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem; border-left: 4px solid var(--accent-color); padding-left: 1rem;">Active Elections</h3>
    
    <?php if (empty($active_elections)): ?>
        <div class="glass-card" style="text-align: center; padding: 3rem;">
            <i class="fas fa-calendar-times" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
            <p style="font-size: 1.2rem; opacity: 0.6;">No active elections at the moment.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach ($active_elections as $election): ?>
                <div class="glass-card election-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <h4 style="font-size: 1.3rem; color: var(--accent-color);"><?php echo htmlspecialchars($election['title']); ?></h4>
                            <span style="background: rgba(85, 239, 196, 0.2); color: #55efc4; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #55efc4;">Active</span>
                        </div>
                        <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 1.5rem;">
                            <?php echo htmlspecialchars($election['description']); ?>
                        </p>
                        <div style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 1rem;">
                            <i class="fas fa-clock"></i> Ends: <?php echo date('M d, Y', strtotime($election['end_date'])); ?>
                        </div>
                    </div>
                    
                    <?php if (in_array($election['id'], $voted_election_ids)): ?>
                        <button class="btn" style="background: rgba(255, 255, 255, 0.1); color: #55efc4; width: 100%; cursor: default;" disabled>
                            <i class="fas fa-check-circle"></i> Already Voted
                        </button>
                    <?php else: ?>
                        <a href="vote.php?id=<?php echo $election['id']; ?>" class="btn btn-primary" style="text-align: center; width: 100%;">
                            Vote Now <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="profile-summary" style="padding: 2rem 0;">
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;">Quick Actions</h3>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="profile.php" class="btn btn-glass"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="results.php" class="btn btn-glass"><i class="fas fa-poll"></i> View Results</a>
            <a href="logout.php" class="btn btn-glass" style="color: #ff7675; border-color: rgba(255, 118, 117, 0.3);"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
