<?php
include 'includes/header.php';

// Fetch all elections
$stmt = $conn->prepare("SELECT * FROM elections ORDER BY created_at DESC");
$stmt->execute();
$elections = $stmt->fetchAll();

$selected_results = null;
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
        
        $total_votes = 0;
        foreach ($candidates_results as $cr) {
            $total_votes += $cr['vote_count'];
        }
        
        $selected_results = [
            'election' => $election_info,
            'results' => $candidates_results,
            'total_votes' => $total_votes
        ];
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Election <span style="color: var(--accent-color);">Results Tracking</span></h2>
    <?php if ($selected_results): ?>
        <button onclick="window.print()" class="btn btn-glass"><i class="fas fa-print"></i> Export to PDF</button>
    <?php endif; ?>
</div>

<?php if ($selected_results): ?>
    <div class="glass-card" style="margin-bottom: 3rem;">
        <a href="results.php" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Back to Overview
        </a>
        <div style="text-align: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.8rem;"><?php echo htmlspecialchars($selected_results['election']['title']); ?></h3>
            <p style="opacity: 0.7;">Final Tally & Participation Report</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($selected_results['results'] as $index => $candidate): 
                $pct = ($selected_results['total_votes'] > 0) ? round(($candidate['vote_count'] / $selected_results['total_votes']) * 100, 1) : 0;
            ?>
                <div class="glass-card" style="text-align: center; position: relative; border-color: <?php echo ($index === 0 && $selected_results['total_votes'] > 0) ? 'var(--accent-color)' : 'var(--glass-border)'; ?>;">
                    <?php if ($index === 0 && $selected_results['total_votes'] > 0): ?>
                        <div style="position: absolute; top: -10px; right: -10px; background: var(--accent-color); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">WINNER</div>
                    <?php endif; ?>
                    
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.1); margin: 0 auto 1rem; overflow: hidden; border: 2px solid var(--accent-color);">
                        <img src="<?php echo $candidate['photo_url'] ?: '../assets/img/voter-avatar.png'; ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($candidate['name']); ?>&background=random'">
                    </div>
                    
                    <h4><?php echo htmlspecialchars($candidate['name']); ?></h4>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-bottom: 1rem;"><?php echo htmlspecialchars($candidate['party']); ?></p>
                    
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--accent-color);"><?php echo $candidate['vote_count']; ?></div>
                    <div style="font-size: 0.9rem; opacity: 0.8;"><?php echo $pct; ?>% of total</div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 3rem; padding: 2rem; background: rgba(0,0,0,0.2); border-radius: 15px; text-align: center;">
            <h4>Report Summary</h4>
            <div style="display: flex; justify-content: center; gap: 3rem; margin-top: 1rem;">
                <div>
                    <div style="font-size: 1.2rem; font-weight: bold;"><?php echo $selected_results['total_votes']; ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.6;">Total Ballots</div>
                </div>
                <div>
                    <div style="font-size: 1.2rem; font-weight: bold;"><?php echo count($selected_results['results']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.6;">Candidates</div>
                </div>
                <div>
                    <div style="font-size: 1.2rem; font-weight: bold;"><?php echo strtoupper($selected_results['election']['status']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.6;">Election Status</div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($elections as $election): ?>
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <h4 style="font-size: 1.2rem;"><?php echo htmlspecialchars($election['title']); ?></h4>
                    <span style="font-size: 0.7rem; opacity: 0.6;"><?php echo date('Y', strtotime($election['created_at'])); ?></span>
                </div>
                <?php
                // Get quick count for card
                $stmt = $conn->prepare("SELECT COUNT(*) FROM votes WHERE election_id = ?");
                $stmt->execute([$election['id']]);
                $count = $stmt->fetchColumn();
                ?>
                <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 1.5rem;">
                    Current Tally: <strong><?php echo $count; ?> Votes Cast</strong>
                </p>
                <div style="display: flex; gap: 10px;">
                    <a href="results.php?id=<?php echo $election['id']; ?>" class="btn btn-primary" style="flex: 1; text-align: center; font-size: 0.85rem;">Detailed Report</a>
                    <a href="elections.php?action=edit&id=<?php echo $election['id']; ?>" class="btn btn-glass" style="font-size: 0.85rem;"><i class="fas fa-cog"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
