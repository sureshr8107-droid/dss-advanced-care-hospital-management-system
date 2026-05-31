<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('index.php');

$result = $conn->query("
    SELECT a.*, d.name as doctor_name, d.specialization, d.fee,
           s.slot_date, s.slot_time
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN slots s ON a.slot_id = s.id
    WHERE a.id = $id AND a.user_id = {$_SESSION['user_id']}
");

if (!$result->num_rows) redirect('index.php');
$appt = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmed – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
@keyframes checkDraw {
    from { stroke-dashoffset: 100; }
    to { stroke-dashoffset: 0; }
}
.success-check { animation: checkDraw 0.5s ease 0.3s both; stroke-dasharray: 100; stroke-dashoffset: 100; }
@media print {
    .navbar, .no-print { display: none !important; }
    .confirm-page { padding: 0 !important; background: white !important; }
    .confirm-card { box-shadow: none !important; }
}
</style>
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar no-print">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div></div></a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="my_appointments.php" class="nav-link">My Appointments</a>
            <a href="logout.php" class="nav-btn-outline">Logout</a>
        </div>
    </div>
</nav>

<div class="confirm-page">
    <div class="confirm-card">
        <div class="confirm-icon">
            <svg width="42" height="42" viewBox="0 0 42 42" fill="none">
                <path class="success-check" d="M8 21 L17 30 L34 12" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
        </div>
        <h2>Booking Confirmed!</h2>
        <p class="conf-sub">Your appointment has been successfully booked at DSS Advanced Care</p>

        <div class="conf-ref">
            <div class="ref-label">Booking Reference</div>
            <div class="ref-num"><?= htmlspecialchars($appt['booking_ref']) ?></div>
            <div style="font-size:11px;color:var(--gray-400);margin-top:6px">Keep this reference for your records</div>
        </div>

        <div class="conf-details">
            <div class="conf-row">
                <span class="cr-label">👨‍⚕️ Doctor</span>
                <span class="cr-value">Dr. <?= htmlspecialchars($appt['doctor_name']) ?></span>
            </div>
            <div class="conf-row">
                <span class="cr-label">🩺 Specialization</span>
                <span class="cr-value"><?= htmlspecialchars($appt['specialization']) ?></span>
            </div>
            <div class="conf-row">
                <span class="cr-label">📅 Date</span>
                <span class="cr-value"><?= date('D, d M Y', strtotime($appt['slot_date'])) ?></span>
            </div>
            <div class="conf-row">
                <span class="cr-label">🕐 Time</span>
                <span class="cr-value"><?= htmlspecialchars($appt['slot_time']) ?></span>
            </div>
            <div class="conf-row">
                <span class="cr-label">👤 Patient</span>
                <span class="cr-value"><?= htmlspecialchars($appt['patient_name']) ?></span>
            </div>
            <div class="conf-row">
                <span class="cr-label">🎂 Age / Gender</span>
                <span class="cr-value"><?= $appt['age'] ?> yrs / <?= htmlspecialchars($appt['gender']) ?></span>
            </div>
            <?php if($appt['disease']): ?>
            <div class="conf-row">
                <span class="cr-label">🏥 Concern</span>
                <span class="cr-value"><?= htmlspecialchars($appt['disease']) ?></span>
            </div>
            <?php endif; ?>
            <div class="conf-row">
                <span class="cr-label">💳 Payment</span>
                <span class="cr-value">
                    <?= $appt['payment_method'] === 'online' ? '✅ Online Paid' : '🏥 Pay at Hospital' ?>
                </span>
            </div>
            <div class="conf-row" style="border:none">
                <span class="cr-label">💰 Amount</span>
                <span class="cr-value" style="color:var(--primary);font-size:18px">₹<?= $appt['fee'] ?></span>
            </div>
        </div>

        <div style="background:#fff8e1;border:1px solid #ffc107;border-radius:12px;padding:16px;margin-bottom:28px;text-align:left;font-size:13px;color:#856404">
            <strong>📋 Important Notes:</strong><br>
            • Please arrive 15 minutes before your appointment<br>
            • Bring this confirmation and any previous medical records<br>
            • DSS Advanced Care is located in Chennai, Tamil Nadu<br>
            • For queries: 044-2245-6789
        </div>

        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap" class="no-print">
            <button onclick="printAppointment()" class="btn-outline-lg" style="padding:12px 28px;font-size:14px">🖨️ Print</button>
            <a href="my_appointments.php" class="btn-outline-lg" style="padding:12px 28px;font-size:14px">📋 My Appointments</a>
            <a href="select_disease.php" class="btn-primary-lg" style="padding:12px 28px;font-size:14px">Book Another →</a>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
