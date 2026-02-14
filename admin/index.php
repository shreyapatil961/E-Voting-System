<?php
include 'includes/header.php';

// Fetch stats
$total_voters = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'voter'")->fetchColumn();
$active_elections = $conn->query("SELECT COUNT(*) FROM elections WHERE status = 'active'")->fetchColumn();
$total_votes = $conn->query("SELECT COUNT(*) FROM votes")->fetchColumn();
$pending_voters = $conn->query("SELECT COUNT(*) FROM users WHERE status = 'pending'")->fetchColumn();

// Fetch recent activity (recent votes)
$stmt = $conn->prepare("
    SELECT v.*, u.full_name as voter_name, e.title as election_title 
    FROM votes v 
    JOIN users u ON v.user_id = u.id 
    JOIN elections e ON v.election_id = e.id 
    ORDER BY v.voted_at DESC LIMIT 5
");
$stmt->execute();
$recent_votes = $stmt->fetchAll();
?>

<h2 style="margin-bottom: 2rem;">Admin <span style="color: var(--accent-color);">Dashboard</span></h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="glass-card" style="text-align: center;">
        <i class="fas fa-users" style="font-size: 2rem; color: var(--accent-color); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.8rem;"><?php echo $total_voters; ?></h3>
        <p style="opacity: 0.7;">Total Voters</p>
    </div>
    <div class="glass-card" style="text-align: center;">
        <i class="fas fa-vote-yea" style="font-size: 2rem; color: #55efc4; margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.8rem;"><?php echo $active_elections; ?></h3>
        <p style="opacity: 0.7;">Active Elections</p>
    </div>
    <div class="glass-card" style="text-align: center;">
        <i class="fas fa-poll" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.8rem;"><?php echo $total_votes; ?></h3>
        <p style="opacity: 0.7;">Total Votes Cast</p>
    </div>
    <div class="glass-card" style="text-align: center;">
        <i class="fas fa-user-clock" style="font-size: 2rem; color: #fab1a0; margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.8rem;"><?php echo $pending_voters; ?></h3>
        <p style="opacity: 0.7;">Pending Approval</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-history" style="color: var(--accent-color);"></i> Recent Voting Activity
        </h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 10px;">Voter</th>
                    <th style="padding: 10px;">Election</th>
                    <th style="padding: 10px;">Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_votes as $vote): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($vote['voter_name']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($vote['election_title']); ?></td>
                    <td style="padding: 12px; opacity: 0.6;"><?php echo date('H:i, d M', strtotime($vote['voted_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($recent_votes)): ?>
                <tr><td colspan="3" style="padding: 20px; text-align: center; opacity: 0.5;">No recent activity.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;">Quick Actions</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <a href="elections.php?action=add" class="btn btn-primary" style="text-align: center; padding: 1.5rem 0;">
                <i class="fas fa-plus-circle"></i> <br> Create Election
            </a>
            <a href="candidates.php?action=add" class="btn btn-glass" style="text-align: center; padding: 1.5rem 0;">
                <i class="fas fa-plus-circle"></i> <br> Add Candidate
            </a>
            <a href="voters.php" class="btn btn-glass" style="text-align: center; padding: 1.5rem 0;">
                <i class="fas fa-user-check"></i> <br> Verify Voters
            </a>
            <a href="results.php" class="btn btn-glass" style="text-align: center; padding: 1.5rem 0;">
                <i class="fas fa-file-export"></i> <br> Export Results
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
