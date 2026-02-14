<?php
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

include 'includes/header.php';

// Fetch all elections (active or completed)
$stmt = $conn->prepare("SELECT * FROM elections ORDER BY created_at DESC");
$stmt->execute();
$elections = $stmt->fetchAll();

// If a specific election is selected, fetch results
$selected_results = null;
$total_votes = 0;
if (isset($_GET['id'])) {
    $election_id = (int)$_GET['id'];
    
    // Fetch election info
    $stmt = $conn->prepare("SELECT * FROM elections WHERE id = ?");
    $stmt->execute([$election_id]);
    $election_info = $stmt->fetch();
    
    if ($election_info) {
        // Fetch candidates and their vote counts
        $stmt = $conn->prepare("
            SELECT c.*, COUNT(v.id) as vote_count 
            FROM candidates c 
            LEFT JOIN votes v ON c.id = v.candidate_id 
            WHERE c.election_id = ? 
            GROUP BY c.id 
            ORDER BY vote_count DESC
        ");
        $stmt->execute([$election_id]);
        $candidates_results = $stmt->fetchAll();
        
        // Total votes for this election
        foreach ($candidates_results as $cr) {
            $total_votes += $cr['vote_count'];
        }
        
        $selected_results = [
            'election' => $election_info,
            'results' => $candidates_results
        ];
    }
}
?>

<div style="padding: 2rem 0;">
    <h2 style="margin-bottom: 2rem; font-size: 2.2rem; text-align: center;">Election <span style="color: var(--accent-color);">Results</span></h2>

    <?php if ($selected_results): ?>
        <div class="glass-card" style="margin-bottom: 3rem;">
            <a href="results.php" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> View All Results
            </a>
            <h3 style="margin-top: 1rem; color: var(--accent-color);"><?php echo htmlspecialchars($selected_results['election']['title']); ?></h3>
            <p style="opacity: 0.8; margin-top: 0.5rem; margin-bottom: 2rem;"><?php echo htmlspecialchars($selected_results['election']['description']); ?></p>

            <div style="margin-top: 2rem;">
                <?php foreach ($selected_results['results'] as $candidate): 
                    $pct = ($total_votes > 0) ? round(($candidate['vote_count'] / $total_votes) * 100, 1) : 0;
                ?>
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-weight: 500;">
                            <span><?php echo htmlspecialchars($candidate['name']); ?> (<?php echo htmlspecialchars($candidate['party']); ?>)</span>
                            <span><?php echo $candidate['vote_count']; ?> Votes (<?php echo $pct; ?>%)</span>
                        </div>
                        <div style="height: 12px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; overflow: hidden;">
                            <div style="width: <?php echo $pct; ?>%; height: 100%; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); transition: width 0.8s ease-out;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--glass-border);">
                <span style="font-size: 1.1rem;">Total Participation: <strong><?php echo $total_votes; ?> Votes</strong></span>
            </div>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach ($elections as $election): ?>
                <div class="glass-card">
                    <h4 style="font-size: 1.3rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($election['title']); ?></h4>
                    <p style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 1.5rem;">
                        Status: <span style="font-weight: 600; color: <?php echo ($election['status'] == 'completed') ? '#ff7675' : '#55efc4'; ?>"><?php echo strtoupper($election['status']); ?></span>
                    </p>
                    <a href="results.php?id=<?php echo $election['id']; ?>" class="btn btn-glass" style="width: 100%; text-align: center;">
                        <i class="fas fa-chart-pie"></i> View Detailed Results
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
