<?php
require_once 'config.php';
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DSS Advanced Care – Premium Hospital, Chennai</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-brand">
            <div class="brand-icon">⚕</div>
            <div><div class="brand-name">DSS Advanced Care</div><div class="brand-tagline">Excellence in Healthcare</div></div>
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-link active">Home</a>
            <a href="doctors.php" class="nav-link">Doctors</a>
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

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <div>
            <div class="hero-badge">🏥 Trusted by 50,000+ Patients in Chennai</div>
            <h1 class="hero-title">Your Health,<br><span class="hero-accent">Our Priority</span></h1>
            <p class="hero-sub">Book appointments with top specialists at DSS Advanced Care, Anna Salai, Chennai. Fast, easy, and secure.</p>
            <div class="hero-actions">
                <a href="<?= isLoggedIn() ? 'select_disease.php' : 'register.php' ?>" class="btn-primary-lg">Book Appointment</a>
                <a href="doctors.php" class="btn-outline-lg">Meet Our Doctors</a>
            </div>
            <div class="hero-stats">
                <div class="stat"><span class="stat-num">11+</span><span class="stat-label">Specialists</span></div>
                <div class="stat"><span class="stat-num">20+</span><span class="stat-label">Departments</span></div>
                <div class="stat"><span class="stat-num">50k+</span><span class="stat-label">Patients</span></div>
                <div class="stat"><span class="stat-num">98%</span><span class="stat-label">Satisfaction</span></div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card floating"><div class="hc-icon">🩺</div><div class="hc-text"><strong>Next Available</strong><span>Today, 10:00 AM</span></div></div>
            <div class="hero-circles"><div class="circle c1"></div><div class="circle c2"></div><div class="circle c3"></div></div>
            <div class="hero-doc-img" style="max-width:240px"><?= getDoctorPortrait(5,'Rashika') ?></div>
        </div>
    </div>
</section>

<section class="diseases-section">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Specializations</span><h2>What's Your Health Concern?</h2><p>Select your condition and we'll match you with the right specialist</p></div>
        <div class="disease-grid">
        <?php
        $diseases=[['name'=>'Fever','icon'=>'🌡️','color'=>'#ff6b6b','bg'=>'#fff5f5'],['name'=>'Skin Problems','icon'=>'🧴','color'=>'#ffa94d','bg'=>'#fff9f0'],['name'=>'Heart Issues','icon'=>'❤️','color'=>'#e74c3c','bg'=>'#fff0f0'],['name'=>'Dental','icon'=>'🦷','color'=>'#74b9ff','bg'=>'#f0f8ff'],['name'=>'Diabetes','icon'=>'🩸','color'=>'#a29bfe','bg'=>'#f8f7ff'],['name'=>'Eye Problems','icon'=>'👁️','color'=>'#55efc4','bg'=>'#f0fff8'],['name'=>'ENT','icon'=>'👂','color'=>'#fdcb6e','bg'=>'#fffaf0'],['name'=>'Orthopedic','icon'=>'🦴','color'=>'#6c5ce7','bg'=>'#f5f3ff'],['name'=>'Neurology','icon'=>'🧠','color'=>'#fd79a8','bg'=>'#fff0f8'],['name'=>'Respiratory','icon'=>'🫁','color'=>'#00cec9','bg'=>'#f0fffe'],['name'=>'General Checkup','icon'=>'🏥','color'=>'#0984e3','bg'=>'#f0f8ff']];
        foreach($diseases as $d):?>
        <a href="<?= isLoggedIn()?'doctors.php?disease='.urlencode($d['name']):'login.php' ?>" class="disease-card" style="--dcolor:<?=$d['color']?>;--dbg:<?=$d['bg']?>">
            <div class="disease-icon"><?=$d['icon']?></div><div class="disease-name"><?=$d['name']?></div><div class="disease-arrow">→</div>
        </a>
        <?php endforeach;?>
        </div>
    </div>
</section>

<section class="how-section">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Process</span><h2>Book in 4 Simple Steps</h2></div>
        <div class="steps-grid">
            <div class="step-card"><div class="step-num">01</div><div class="step-icon">📋</div><h3>Select Condition</h3><p>Choose your health concern from our list</p></div>
            <div class="step-card"><div class="step-num">02</div><div class="step-icon">👨‍⚕️</div><h3>Pick a Doctor</h3><p>Browse specialists and view their availability</p></div>
            <div class="step-card"><div class="step-num">03</div><div class="step-icon">📅</div><h3>Choose a Slot</h3><p>Select a convenient date and time</p></div>
            <div class="step-card"><div class="step-num">04</div><div class="step-icon">✅</div><h3>Confirm Booking</h3><p>Fill details, pay and receive confirmation</p></div>
        </div>
    </div>
</section>

<section class="featured-section">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Our Team</span><h2>Meet Our Expert Doctors</h2><p>World-class specialists dedicated to your wellbeing</p></div>
        <div class="doctors-row">
        <?php $result=$conn->query("SELECT * FROM doctors LIMIT 4"); while($doc=$result->fetch_assoc()):?>
        <div class="doc-card">
            <div class="doc-card-img"><?= getDoctorPortrait($doc['id'],$doc['name']) ?></div>
            <div class="doc-card-body">
                <h3><?=htmlspecialchars($doc['name'])?></h3>
                <p class="doc-spec"><?=htmlspecialchars($doc['specialization'])?></p>
                <div class="doc-meta"><span>⭐ <?=$doc['rating']?></span><span>📅 <?=$doc['experience']?> yrs</span><span>💰 ₹<?=$doc['fee']?></span></div>
                <a href="<?=isLoggedIn()?'book_appointment.php?doctor_id='.$doc['id']:'login.php'?>" class="doc-book-btn">Book Now</a>
            </div>
        </div>
        <?php endwhile;?>
        </div>
        <div style="text-align:center;margin-top:40px"><a href="doctors.php" class="btn-outline-lg">View All Doctors →</a></div>
    </div>
</section>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-brand"><div class="brand-icon large">⚕</div><div><div class="footer-name">DSS Advanced Care</div><div class="footer-tagline">Excellence in Healthcare Since 2005</div></div></div>
        <div class="footer-links">
            <div><strong>Quick Links</strong><a href="index.php">Home</a><a href="doctors.php">Doctors</a><a href="login.php">Login</a><a href="register.php">Register</a></div>
            <div><strong>Contact Us</strong><span>📞 044-2245-6789</span><span>✉️ info@dsscare.com</span><span>📍 No. 14, Anna Salai, Teynampet</span><span>Chennai – 600 018, Tamil Nadu</span></div>
        </div>
    </div>
    <div class="footer-bottom">© 2024 DSS Advanced Care Hospital, Chennai – 600 018. All rights reserved.</div>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
