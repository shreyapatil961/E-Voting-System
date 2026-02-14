<?php
include 'includes/config.php';
include 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$full_name, $email, $password])) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<section class="register-section" style="padding: 60px 0; display: flex; justify-content: center;">
    <div class="glass-card" style="width: 100%; max-width: 500px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Voter <span style="color: var(--accent-color);">Registration</span></h2>
        
        <?php if($error): ?>
            <div style="background: rgba(255, 118, 117, 0.2); border: 1px solid #ff7675; padding: 10px; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div style="background: rgba(85, 239, 196, 0.2); border: 1px solid #55efc4; padding: 10px; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                <?php echo $success; ?>
                <br><a href="login.php" style="color: white; font-weight: bold;">Go to Login</a>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="full_name" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--glass-border); background: rgba(255, 255, 255, 0.1); color: white; outline: none;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--glass-border); background: rgba(255, 255, 255, 0.1); color: white; outline: none;">
            </div>
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--glass-border); background: rgba(255, 255, 255, 0.1); color: white; outline: none;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Create Secure Account</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; opacity: 0.8;">
            Already have an account? <a href="login.php" style="color: var(--accent-color); text-decoration: none;">Login here</a>
        </p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
