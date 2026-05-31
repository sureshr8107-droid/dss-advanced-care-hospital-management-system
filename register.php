<?php
require_once 'config.php';
if (isLoggedIn()) redirect('index.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($conn, $_POST['full_name'] ?? '');
    $email = sanitize($conn, $_POST['email'] ?? '');
    $phone = sanitize($conn, $_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $cpass = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$phone || !$pass) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $cpass) {
        $error = 'Passwords do not match.';
    } else {
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = 'Email already registered. Please login.';
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (full_name, email, phone, password) VALUES ('$name','$email','$phone','$hashed')");
            $_SESSION['user_id']   = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email']= $email;
            redirect('select_disease.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar"><div class="nav-container"><a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Excellence in Healthcare</div></div></a></div></nav>

<div class="auth-page">
    <div class="auth-card">
        <h2>Create Account</h2>
        <p class="subtitle">Join DSS Advanced Care to book appointments easily</p>
        <?php if($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" value="<?= htmlspecialchars($_POST['full_name']??'') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="form-control" placeholder="9876543210" value="<?= htmlspecialchars($_POST['phone']??'') ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                </div>
            </div>
            <button type="submit" class="btn-block">Create Account →</button>
        </form>
        <div class="auth-switch">Already have an account? <a href="login.php">Login here</a></div>
        <div class="auth-switch" style="margin-top:10px"><a href="doctor_login.php" style="color:#666">Doctor? Login here →</a></div>
    </div>
</div>
</body>
</html>
