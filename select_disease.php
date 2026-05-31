<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Your Concern – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
.select-page { padding: 100px 24px 60px; min-height: 100vh; background: linear-gradient(135deg, #f0f7ff, #e8f4fd); }
.select-container { max-width: 1000px; margin: 0 auto; }
.select-header { text-align: center; margin-bottom: 56px; }
.select-header h1 { font-family: var(--font-display); font-size: 42px; color: var(--gray-800); margin-bottom: 12px; }
.select-header p { font-size: 17px; color: var(--gray-400); }
.welcome-bar { background: white; border-radius: 16px; padding: 20px 28px; margin-bottom: 40px; display: flex; align-items: center; gap: 16px; border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); }
.welcome-avatar { width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; color: white; }
.disease-grid-lg { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 18px; }
.disease-card-lg {
    background: white; border: 2px solid var(--gray-200);
    border-radius: 20px; padding: 32px 20px;
    text-align: center; text-decoration: none;
    color: var(--gray-800); transition: all 0.3s;
    display: block; cursor: pointer;
}
.disease-card-lg:hover { transform: translateY(-8px); border-color: var(--dcolor); box-shadow: 0 16px 40px rgba(0,0,0,0.12); background: var(--dbg,white); }
.disease-card-lg .di { font-size: 52px; margin-bottom: 16px; display: block; }
.disease-card-lg .dn { font-weight: 700; font-size: 14px; }
.disease-card-lg .ds { font-size: 12px; color: var(--gray-400); margin-top: 6px; }
</style>
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div></div></a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="my_appointments.php" class="nav-link">My Appointments</a>
            <a href="logout.php" class="nav-btn-outline">Logout</a>
        </div>
    </div>
</nav>

<div class="select-page">
    <div class="select-container">
        <div class="welcome-bar">
            <div class="welcome-avatar">👋</div>
            <div>
                <strong>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?>!</strong>
                <div style="font-size:13px;color:var(--gray-400)">Select your health concern to find the right specialist</div>
            </div>
        </div>
        <div class="select-header">
            <h1>What's Your Concern?</h1>
            <p>Choose from our comprehensive list of specializations</p>
        </div>
        <div class="disease-grid-lg">
        <?php
        $diseases = [
            ['name'=>'Fever','icon'=>'🌡️','color'=>'#ff6b6b','bg'=>'#fff5f5','spec'=>'General Physician'],
            ['name'=>'Skin Problems','icon'=>'🧴','color'=>'#ffa94d','bg'=>'#fff9f0','spec'=>'Dermatologist'],
            ['name'=>'Heart Issues','icon'=>'❤️','color'=>'#e74c3c','bg'=>'#fff0f0','spec'=>'Cardiologist'],
            ['name'=>'Dental','icon'=>'🦷','color'=>'#74b9ff','bg'=>'#f0f8ff','spec'=>'Dentist'],
            ['name'=>'Diabetes','icon'=>'🩸','color'=>'#a29bfe','bg'=>'#f8f7ff','spec'=>'Diabetologist'],
            ['name'=>'Eye Problems','icon'=>'👁️','color'=>'#55efc4','bg'=>'#f0fff8','spec'=>'Ophthalmologist'],
            ['name'=>'ENT','icon'=>'👂','color'=>'#fdcb6e','bg'=>'#fffaf0','spec'=>'ENT Specialist'],
            ['name'=>'Orthopedic','icon'=>'🦴','color'=>'#6c5ce7','bg'=>'#f5f3ff','spec'=>'Orthopedic'],
            ['name'=>'Neurology','icon'=>'🧠','color'=>'#fd79a8','bg'=>'#fff0f8','spec'=>'Neurologist'],
            ['name'=>'Respiratory','icon'=>'🫁','color'=>'#00cec9','bg'=>'#f0fffe','spec'=>'Pulmonologist'],
            ['name'=>'General Checkup','icon'=>'🏥','color'=>'#0984e3','bg'=>'#f0f8ff','spec'=>'General Physician'],
        ];
        foreach($diseases as $d):
        ?>
        <a href="doctors.php?disease=<?= urlencode($d['name']) ?>" class="disease-card-lg" style="--dcolor:<?= $d['color'] ?>;--dbg:<?= $d['bg'] ?>">
            <span class="di"><?= $d['icon'] ?></span>
            <div class="dn"><?= $d['name'] ?></div>
            <div class="ds"><?= $d['spec'] ?></div>
        </a>
        <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
