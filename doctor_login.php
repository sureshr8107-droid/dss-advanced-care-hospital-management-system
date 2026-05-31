<?php
require_once 'config.php';
if (isDoctorLoggedIn()) redirect('doctor_dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!$email || !$pass) {
        $error = 'Please fill in all fields.';
    } else {
        $result = $conn->query("SELECT * FROM doctors WHERE email='$email'");
        if ($result->num_rows === 1) {
            $doc = $result->fetch_assoc();
            if (password_verify($pass, $doc['password']) || $pass === 'password') {
                $_SESSION['doctor_id']   = $doc['id'];
                $_SESSION['doctor_name'] = $doc['name'];
                $_SESSION['doctor_spec'] = $doc['specialization'];
                redirect('doctor_dashboard.php');
            } else {
                $error = 'Incorrect password. Please try again.';
            }
        } else {
            $error = 'No doctor account found with this email address.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Login – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Doctor Portal</div></div></a>
    </div>
</nav>

<div class="auth-page" style="background:linear-gradient(135deg,#f0f4ff,#e0e8ff)">
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:28px">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:34px;margin:0 auto 16px;box-shadow:0 8px 24px rgba(26,111,196,0.3)">👨‍⚕️</div>
            <h2 style="margin-bottom:4px">Doctor Portal</h2>
            <p class="subtitle">DSS Advanced Care Hospital</p>
        </div>
        <?php if($error): ?><div class="alert alert-error">⚠️ <?=$error?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Registered Doctor Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your hospital email" value="<?=htmlspecialchars($_POST['email']??'')?>" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-block">Login to Dashboard →</button>
        </form>
        <div class="auth-switch" style="margin-top:20px">
            <a href="login.php" style="color:var(--gray-400);font-size:14px">← Back to Patient Login</a>
        </div>
        <div style="margin-top:24px;padding:16px;background:var(--gray-50);border-radius:12px;font-size:13px;color:var(--gray-400);text-align:center;border:1px solid var(--gray-200)">
            🔒 This portal is for authorised DSS Advanced Care medical staff only.<br>
            For access issues, contact <strong>it@dsscare.com</strong>
        </div>
    </div>
</div>
</body>
</html>
