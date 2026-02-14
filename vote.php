<?php
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$election_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$election_id) {
    redirect('dashboard.php');
}

// Check if user already voted in this election
$stmt = $conn->prepare("SELECT id FROM votes WHERE user_id = ? AND election_id = ?");
$stmt->execute([$user_id, $election_id]);
if ($stmt->fetch()) {
    redirect('dashboard.php?msg=already_voted');
}

// Fetch election details
$stmt = $conn->prepare("SELECT * FROM elections WHERE id = ? AND status = 'active'");
$stmt->execute([$election_id]);
$election = $stmt->fetch();

if (!$election) {
    redirect('dashboard.php');
}

// Handle vote submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_id'])) {
    $candidate_id = (int)$_POST['candidate_id'];
    
    // Insert vote
    try {
        $stmt = $conn->prepare("INSERT INTO votes (user_id, election_id, candidate_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $election_id, $candidate_id])) {
            redirect('dashboard.php?msg=vote_success');
        }
    } catch (PDOException $e) {
        $message = "Error casting vote: " . $e->getMessage();
    }
}

// Fetch candidates
$stmt = $conn->prepare("SELECT * FROM candidates WHERE election_id = ?");
$stmt->execute([$election_id]);
$candidates = $stmt->fetchAll();

include 'includes/header.php';
?>

<div style="padding: 2rem 0;">
    <div class="glass-card" style="margin-bottom: 2rem;">
        <a href="dashboard.php" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <h2 style="margin-top: 1rem; font-size: 2rem;"><?php echo htmlspecialchars($election['title']); ?></h2>
        <p style="opacity: 0.8; margin-top: 0.5rem;"><?php echo htmlspecialchars($election['description']); ?></p>
    </div>

    <?php if($message): ?>
        <div style="background: rgba(255, 118, 117, 0.2); border: 1px solid #ff7675; padding: 15px; border-radius: 10px; margin-bottom: 2rem;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h3 style="margin-bottom: 1.5rem;">Select your candidate:</h3>

    <form action="vote.php?id=<?php echo $election_id; ?>" method="POST">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($candidates as $candidate): ?>
                <div class="glass-card candidate-card" style="text-align: center; transition: 0.3s; position: relative;">
                    <div style="width: 120px; height: 120px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); margin: 0 auto 1rem; border: 2px solid var(--accent-color); padding: 5px; overflow: hidden;">
                        <?php if($candidate['photo_url']): ?>
                            <img src="<?php echo htmlspecialchars($candidate['photo_url']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                            <i class="fas fa-user" style="font-size: 4rem; color: var(--accent-color); margin-top: 20px;"></i>
                        <?php endif; ?>
                    </div>
                    
                    <h4 style="font-size: 1.2rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($candidate['name']); ?></h4>
                    <p style="color: var(--accent-color); font-weight: 600; margin-bottom: 1rem;"><?php echo htmlspecialchars($candidate['party']); ?></p>
                    <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php echo htmlspecialchars($candidate['bio']); ?>
                    </p>
                    
                    <label class="vote-checkbox" style="display: block; cursor: pointer;">
                        <input type="radio" name="candidate_id" value="<?php echo $candidate['id']; ?>" required style="display: none;">
                        <div class="vote-indicator" style="background: rgba(255, 255, 255, 0.1); border: 2px solid var(--glass-border); padding: 0.8rem; border-radius: 10px; font-weight: 600; transition: 0.3s;">
                            Select Candidate
                        </div>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 3rem; text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3rem; font-size: 1.1rem; box-shadow: 0 10px 25px rgba(74, 144, 226, 0.4);">
                <i class="fas fa-paper-plane" style="margin-right: 10px;"></i> Cast Your Vote Securely
            </button>
            <p style="margin-top: 1rem; opacity: 0.6; font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Once cast, your vote cannot be changed.
            </p>
        </div>
    </form>
</div>

<style>
    input[type="radio"]:checked + .vote-indicator {
        background: var(--accent-color) !important;
        color: white !important;
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 15px rgba(0, 206, 201, 0.5);
    }
    .candidate-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }
</style>

<?php include 'includes/footer.php'; ?>
