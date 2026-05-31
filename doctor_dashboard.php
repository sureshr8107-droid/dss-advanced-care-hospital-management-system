<?php
require_once 'config.php';
require_once 'includes/header.php';
if (!isDoctorLoggedIn()) redirect('doctor_login.php');

$doctor_id = $_SESSION['doctor_id'];
$docResult = $conn->query("SELECT * FROM doctors WHERE id=$doctor_id");
$doctor = $docResult->fetch_assoc();

// Stats
$totalAppts = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE doctor_id=$doctor_id")->fetch_assoc()['c'];
$todayAppts = $conn->query("SELECT COUNT(*) as c FROM appointments a JOIN slots s ON a.slot_id=s.id WHERE a.doctor_id=$doctor_id AND s.slot_date=CURDATE()")->fetch_assoc()['c'];
$upcomingAppts = $conn->query("SELECT COUNT(*) as c FROM appointments a JOIN slots s ON a.slot_id=s.id WHERE a.doctor_id=$doctor_id AND s.slot_date>=CURDATE()")->fetch_assoc()['c'];
$revenue = $conn->query("SELECT SUM(d.fee) as r FROM appointments a JOIN doctors d ON a.doctor_id=d.id WHERE a.doctor_id=$doctor_id")->fetch_assoc()['r'] ?? 0;

// Filter
$filter = sanitize($conn, $_GET['filter'] ?? 'all');
$dateFilter = '';
if ($filter === 'today') $dateFilter = "AND s.slot_date = CURDATE()";
elseif ($filter === 'upcoming') $dateFilter = "AND s.slot_date >= CURDATE()";
elseif ($filter === 'past') $dateFilter = "AND s.slot_date < CURDATE()";

$appointments = $conn->query("
    SELECT a.*, s.slot_date, s.slot_time,
           u.full_name as patient_user_name, u.email as patient_email, u.phone as patient_phone
    FROM appointments a
    JOIN slots s ON a.slot_id = s.id
    JOIN users u ON a.user_id = u.id
    WHERE a.doctor_id = $doctor_id $dateFilter
    ORDER BY s.slot_date ASC, s.slot_time ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Dashboard – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
.appt-row-detail { display: none; background: var(--gray-50); }
.appt-row-detail.open { display: table-row; }
.detail-box { padding: 20px 24px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 16px; }
.detail-item { }
.detail-item .di-label { font-size: 11px; color: var(--gray-400); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.detail-item .di-val { font-size: 14px; color: var(--gray-800); font-weight: 600; }
.expand-btn { background: none; border: none; cursor: pointer; color: var(--primary); font-size: 18px; padding: 4px; }
@media(max-width:768px) { table, thead, tbody, th, td, tr { display: block; } thead tr { display: none; } tbody td { display: flex; justify-content: space-between; padding: 8px 16px; } }
</style>
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Doctor Portal</div></div></a>
        <div class="nav-links">
            <span style="font-size:14px;color:var(--gray-400)">Dr. <?= htmlspecialchars($_SESSION['doctor_name']) ?></span>
            <a href="doctor_logout.php" class="nav-btn-outline">Logout</a>
        </div>
    </div>
</nav>

<div class="dashboard-page">
    <div class="dash-header">
        <div class="dash-header-inner">
            <h1>Good <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, Dr. <?= htmlspecialchars($doctor['name']) ?> 👋</h1>
            <p><?= htmlspecialchars($doctor['specialization']) ?> · DSS Advanced Care Hospital</p>
        </div>
    </div>

    <div class="dash-stats">
        <div class="dash-stat-card">
            <div class="dsc-num"><?= $todayAppts ?></div>
            <div class="dsc-label">📅 Today's Appointments</div>
        </div>
        <div class="dash-stat-card">
            <div class="dsc-num"><?= $upcomingAppts ?></div>
            <div class="dsc-label">⏰ Upcoming</div>
        </div>
        <div class="dash-stat-card">
            <div class="dsc-num"><?= $totalAppts ?></div>
            <div class="dsc-label">📋 Total Appointments</div>
        </div>
        <div class="dash-stat-card">
            <div class="dsc-num">₹<?= number_format($revenue) ?></div>
            <div class="dsc-label">💰 Total Revenue</div>
        </div>
    </div>

    <div class="dash-content">
        <div class="appointments-table-wrap">
            <div class="table-header">
                <h3>Patient Appointments</h3>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="?filter=all" class="filter-btn <?= $filter==='all'?'active':'' ?>" style="padding:7px 14px;font-size:12px">All</a>
                    <a href="?filter=today" class="filter-btn <?= $filter==='today'?'active':'' ?>" style="padding:7px 14px;font-size:12px">Today</a>
                    <a href="?filter=upcoming" class="filter-btn <?= $filter==='upcoming'?'active':'' ?>" style="padding:7px 14px;font-size:12px">Upcoming</a>
                    <a href="?filter=past" class="filter-btn <?= $filter==='past'?'active':'' ?>" style="padding:7px 14px;font-size:12px">Past</a>
                </div>
            </div>
            <?php if($appointments->num_rows === 0): ?>
            <div style="text-align:center;padding:60px;color:var(--gray-400)">
                <div style="font-size:48px;margin-bottom:16px">📭</div>
                <p>No appointments found for this filter</p>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Concern</th>
                        <th>Age/Gender</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; while($appt = $appointments->fetch_assoc()):
                    $isPast = strtotime($appt['slot_date']) < strtotime('today');
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <strong><?= htmlspecialchars($appt['patient_name']) ?></strong><br>
                        <span style="font-size:12px;color:var(--gray-400)"><?= htmlspecialchars($appt['patient_phone']) ?></span>
                    </td>
                    <td>
                        <strong><?= date('d M Y', strtotime($appt['slot_date'])) ?></strong><br>
                        <span style="font-size:12px;color:var(--gray-400)"><?= htmlspecialchars($appt['slot_time']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($appt['disease'] ?: '—') ?></td>
                    <td><?= $appt['age'] ?> yrs / <?= htmlspecialchars($appt['gender']) ?></td>
                    <td>
                        <span class="badge <?= $appt['payment_method']==='online' ? 'badge-success' : 'badge-warning' ?>">
                            <?= $appt['payment_method']==='online' ? 'Paid' : 'At Hospital' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $isPast ? 'badge-info' : 'badge-success' ?>">
                            <?= $isPast ? 'Done' : 'Upcoming' ?>
                        </span>
                    </td>
                    <td>
                        <button class="expand-btn" onclick="toggleDetail('det-<?= $appt['id'] ?>', this)" title="View Details">▼</button>
                    </td>
                </tr>
                <tr class="appt-row-detail" id="det-<?= $appt['id'] ?>">
                    <td colspan="8">
                        <div class="detail-box">
                            <div class="detail-item"><div class="di-label">Booking Ref</div><div class="di-val"><?= htmlspecialchars($appt['booking_ref']) ?></div></div>
                            <div class="detail-item"><div class="di-label">Email</div><div class="di-val"><?= htmlspecialchars($appt['patient_email']) ?></div></div>
                            <?php if($appt['height']): ?><div class="detail-item"><div class="di-label">Height</div><div class="di-val"><?= $appt['height'] ?> cm</div></div><?php endif; ?>
                            <?php if($appt['weight']): ?><div class="detail-item"><div class="di-label">Weight</div><div class="di-val"><?= $appt['weight'] ?> kg</div></div><?php endif; ?>
                            <?php if($appt['food_pref']): ?><div class="detail-item"><div class="di-label">Food Pref</div><div class="di-val"><?= ucfirst(htmlspecialchars($appt['food_pref'])) ?></div></div><?php endif; ?>
                            <?php if($appt['illness_description']): ?><div class="detail-item" style="grid-column:1/-1"><div class="di-label">Illness Description</div><div class="di-val" style="font-weight:400;color:var(--gray-600)"><?= htmlspecialchars($appt['illness_description']) ?></div></div><?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script>
function toggleDetail(id, btn) {
    const row = document.getElementById(id);
    row.classList.toggle('open');
    btn.textContent = row.classList.contains('open') ? '▲' : '▼';
}
</script>
</body>
</html>
