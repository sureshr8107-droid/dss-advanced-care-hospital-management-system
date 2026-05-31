<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$appointments = $conn->query("
    SELECT a.*, d.name as doctor_name, d.specialization, d.fee,
           s.slot_date, s.slot_time
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN slots s ON a.slot_id = s.id
    WHERE a.user_id = $user_id
    ORDER BY s.slot_date DESC, s.slot_time DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Appointments – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
.appt-card { background: white; border: 1px solid var(--gray-200); border-radius: 16px; padding: 24px; margin-bottom: 16px; display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: center; transition: all 0.2s; }
.appt-card:hover { box-shadow: var(--shadow-md); }
.appt-icon { width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg,var(--primary),var(--primary-dark)); display:flex;align-items:center;justify-content:center;font-size:24px;color:white;flex-shrink:0 }
.appt-info h4 { font-family: var(--font-display); font-size: 18px; margin-bottom: 4px; }
.appt-info .ai-meta { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px; }
.appt-info .ai-meta span { font-size: 13px; color: var(--gray-400); }
.appt-ref { text-align: right; }
.appt-ref .ar-ref { font-family: monospace; font-size: 13px; color: var(--primary); font-weight: 700; background: rgba(26,111,196,0.08); padding: 4px 10px; border-radius: 6px; }
.appt-ref .ar-fee { font-size: 18px; font-weight: 700; color: var(--gray-800); margin-top: 8px; }
@media(max-width:600px){ .appt-card { grid-template-columns: auto 1fr; } .appt-ref { display: none; } }
</style>
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div></div></a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="doctors.php" class="nav-link">Doctors</a>
            <a href="my_appointments.php" class="nav-link active">My Appointments</a>
            <a href="logout.php" class="nav-btn-outline">Logout</a>
        </div>
    </div>
</nav>

<div class="page-hero" style="padding:100px 24px 48px">
    <h1>My Appointments</h1>
    <p>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
</div>

<div style="max-width:900px;margin:0 auto;padding:40px 24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;flex-wrap:wrap;gap:12px">
        <h2 style="font-family:var(--font-display);font-size:24px">
            <?= $appointments->num_rows ?> Appointment<?= $appointments->num_rows!==1?'s':'' ?>
        </h2>
        <a href="select_disease.php" class="btn-primary-lg" style="padding:12px 24px;font-size:14px">+ Book New</a>
    </div>

    <?php if($appointments->num_rows === 0): ?>
    <div style="text-align:center;padding:80px 24px;background:white;border-radius:16px;border:1px solid var(--gray-200)">
        <div style="font-size:64px;margin-bottom:16px">📋</div>
        <h3 style="font-size:22px;margin-bottom:8px;font-family:var(--font-display)">No appointments yet</h3>
        <p style="color:var(--gray-400);margin-bottom:28px">Book your first appointment with our specialists</p>
        <a href="select_disease.php" class="btn-primary-lg">Book Appointment →</a>
    </div>
    <?php else: ?>
    <?php while($appt = $appointments->fetch_assoc()):
        $isPast = strtotime($appt['slot_date']) < strtotime('today');
        $icons = ['Cardiologist'=>'❤️','Dermatologist'=>'🧴','Dentist'=>'🦷','Neurologist'=>'🧠','Orthopedic Specialist'=>'🦴','Eye Specialist'=>'👁️','Gynecologist'=>'👶','Diabetologist'=>'🩸','ENT Specialist'=>'👂','General Physician'=>'🩺','General Surgeon'=>'🔪'];
        $icon = $icons[$appt['specialization']] ?? '🏥';
    ?>
    <div class="appt-card">
        <div class="appt-icon"><?= $icon ?></div>
        <div class="appt-info">
            <h4>Dr. <?= htmlspecialchars($appt['doctor_name']) ?></h4>
            <p style="font-size:13px;color:var(--primary);font-weight:600"><?= htmlspecialchars($appt['specialization']) ?></p>
            <div class="ai-meta">
                <span>📅 <?= date('D, d M Y', strtotime($appt['slot_date'])) ?></span>
                <span>🕐 <?= htmlspecialchars($appt['slot_time']) ?></span>
                <span>👤 <?= htmlspecialchars($appt['patient_name']) ?></span>
                <?php if($appt['disease']): ?><span>🏥 <?= htmlspecialchars($appt['disease']) ?></span><?php endif; ?>
            </div>
            <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                <span class="badge <?= $isPast ? 'badge-info' : 'badge-success' ?>">
                    <?= $isPast ? 'Completed' : 'Upcoming' ?>
                </span>
                <span class="badge <?= $appt['payment_method']==='online' ? 'badge-success' : 'badge-warning' ?>">
                    <?= $appt['payment_method']==='online' ? '✅ Paid Online' : '🏥 Pay at Hospital' ?>
                </span>
            </div>
        </div>
        <div class="appt-ref">
            <div class="ar-ref"><?= htmlspecialchars($appt['booking_ref']) ?></div>
            <div class="ar-fee">₹<?= $appt['fee'] ?></div>
            <a href="confirmation.php?id=<?= $appt['id'] ?>" style="font-size:12px;color:var(--primary);text-decoration:none;display:block;margin-top:8px">View Details →</a>
        </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>
</div>

<footer class="footer">
    <div class="footer-bottom">© 2024 DSS Advanced Care Hospital. All rights reserved.</div>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
