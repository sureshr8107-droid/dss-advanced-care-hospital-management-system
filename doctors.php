<?php
require_once 'config.php';
require_once 'includes/header.php';

$disease = sanitize($conn, $_GET['disease'] ?? '');
$search  = sanitize($conn, $_GET['search'] ?? '');
$where = "1=1";
if ($disease) $where .= " AND FIND_IN_SET('$disease', disease_tags)";
if ($search)  $where .= " AND (name LIKE '%$search%' OR specialization LIKE '%$search%')";
$doctors = $conn->query("SELECT * FROM doctors WHERE $where ORDER BY rating DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Doctors – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Excellence in Healthcare</div></div></a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="doctors.php" class="nav-link active">Doctors</a>
            <?php if(isLoggedIn()): ?>
                <a href="my_appointments.php" class="nav-link">My Appointments</a>
                <a href="logout.php" class="nav-btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-btn">Register</a>
            <?php endif; ?>
        </div>
        <button class="nav-toggle" onclick="toggleMobileMenu()">☰</button>
    </div>
    <div class="mobile-menu" id="mobileMenu">
        <a href="index.php">Home</a><a href="doctors.php">Doctors</a>
        <?php if(isLoggedIn()): ?><a href="my_appointments.php">My Appointments</a><a href="logout.php">Logout</a>
        <?php else: ?><a href="login.php">Login</a><a href="register.php">Register</a><?php endif; ?>
    </div>
</nav>

<div class="page-hero">
    <h1>Our Expert Doctors</h1>
    <p><?= $disease ? "Specialists for: <strong>$disease</strong>" : "World-class specialists at Anna Salai, Chennai" ?></p>
</div>

<div style="background:white;padding:16px 24px;border-bottom:1px solid #e2e8f0;">
    <div style="max-width:1280px;margin:0 auto;">
        <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <?php if($disease): ?><input type="hidden" name="disease" value="<?=htmlspecialchars($disease)?>"> <?php endif; ?>
            <input type="text" name="search" class="form-control" placeholder="Search by name or specialization..." value="<?=htmlspecialchars($search)?>" style="max-width:400px;margin:0">
            <button type="submit" class="nav-btn" style="padding:12px 24px">Search</button>
            <?php if($disease||$search): ?><a href="doctors.php" style="font-size:14px;color:var(--gray-400);text-decoration:none;">✕ Clear</a><?php endif; ?>
        </form>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-inner">
        <button class="filter-btn <?=!$disease?'active':''?>" onclick="window.location='doctors.php'">All Doctors</button>
        <?php foreach(['Fever','Skin Problems','Heart Issues','Dental','Diabetes','Eye Problems','ENT','Orthopedic','Neurology','Respiratory','General Checkup'] as $d): ?>
        <button class="filter-btn <?=$disease===$d?'active':''?>" onclick="window.location='doctors.php?disease=<?=urlencode($d)?>'"> <?=$d?></button>
        <?php endforeach; ?>
    </div>
</div>

<div class="doctors-grid-page">
<?php if($doctors->num_rows===0): ?>
    <div style="grid-column:1/-1;text-align:center;padding:80px 24px;color:var(--gray-400)">
        <div style="font-size:64px;margin-bottom:16px">🔍</div>
        <h3 style="font-size:22px;margin-bottom:8px">No doctors found</h3>
        <p>Try clearing filters or searching differently</p>
        <a href="doctors.php" class="btn-primary-lg" style="display:inline-block;margin-top:24px">View All Doctors</a>
    </div>
<?php else: while($doc=$doctors->fetch_assoc()): ?>
<div class="doc-card-full">
    <div class="doc-card-img"><?= getDoctorPortrait($doc['id'],$doc['name']) ?></div>
    <div class="doc-card-body">
        <h3><?=htmlspecialchars($doc['name'])?></h3>
        <p class="doc-spec">🩺 <?=htmlspecialchars($doc['specialization'])?></p>
        <p class="doc-bio"><?=htmlspecialchars($doc['bio'])?></p>
        <div class="doc-tags"><?php foreach(explode(',',$doc['disease_tags']) as $tag): ?><span class="doc-tag"><?=trim($tag)?></span><?php endforeach; ?></div>
        <div class="doc-meta">
            <span>⭐ <?=$doc['rating']?> Rating</span>
            <span>📅 <?=$doc['experience']?> yrs exp</span>
            <span>💰 ₹<?=$doc['fee']?></span>
        </div>
        <?php if(isLoggedIn()): ?>
        <a href="book_appointment.php?doctor_id=<?=$doc['id']?><?=$disease?'&disease='.urlencode($disease):''?>" class="doc-book-btn">Book Appointment →</a>
        <?php else: ?>
        <a href="login.php" class="doc-book-btn">Login to Book →</a>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; endif; ?>
</div>

<footer class="footer">
    <div class="footer-bottom">© 2024 DSS Advanced Care Hospital, No. 14 Anna Salai, Teynampet, Chennai – 600 018. All rights reserved.</div>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
