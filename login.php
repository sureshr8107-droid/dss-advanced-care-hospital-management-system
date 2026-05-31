<?php
require_once 'config.php';
if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!$email || !$pass) {
        $error = 'Please fill in all fields.';
    } else {
        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email']= $user['email'];
                $redirect = $_GET['redirect'] ?? 'select_disease.php';
                redirect($redirect);
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No account found with this email.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar"><div class="nav-container"><a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Excellence in Healthcare</div></div></a></div></nav>

<div class="auth-page">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Log in to manage your appointments</p>
        <?php if($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>
        <?php if(isset($_GET['msg'])): ?><div class="alert alert-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your password" required>
            </div>
            <button type="submit" class="btn-block">Login →</button>
        </form>
        <div class="auth-switch">Don't have an account? <a href="register.php">Register free</a></div>
        <div class="auth-switch" style="margin-top:10px"><a href="doctor_login.php" style="color:#666">Doctor? Login here →</a></div>
    </div>
</div>
</body>
</html>
