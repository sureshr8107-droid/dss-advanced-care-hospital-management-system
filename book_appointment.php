<?php
require_once 'config.php';
require_once 'includes/header.php';
if (!isLoggedIn()) redirect('login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));

$doctor_id = (int)($_GET['doctor_id'] ?? 0);
$disease   = sanitize($conn, $_GET['disease'] ?? '');

if (!$doctor_id) redirect('doctors.php');

$docResult = $conn->query("SELECT * FROM doctors WHERE id=$doctor_id");
if (!$docResult->num_rows) redirect('doctors.php');
$doctor = $docResult->fetch_assoc();

// Generate next 7 days of slots
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i day"));
}

// Fetch slots for this doctor grouped by date
$slotData = [];
foreach ($dates as $date) {
    $slotRes = $conn->query("SELECT * FROM slots WHERE doctor_id=$doctor_id AND slot_date='$date' ORDER BY slot_time");
    $slotData[$date] = [];
    while ($s = $slotRes->fetch_assoc()) {
        $slotData[$date][] = $s;
    }
}

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id     = (int)($_POST['slot_id'] ?? 0);
    $pat_name    = sanitize($conn, $_POST['patient_name'] ?? '');
    $age         = (int)($_POST['age'] ?? 0);
    $gender      = sanitize($conn, $_POST['gender'] ?? '');
    $height      = (float)($_POST['height'] ?? 0);
    $weight      = (float)($_POST['weight'] ?? 0);
    $food_pref   = sanitize($conn, $_POST['food_pref'] ?? '');
    $illness     = sanitize($conn, $_POST['illness_description'] ?? '');
    $pay_method  = sanitize($conn, $_POST['payment_method'] ?? 'pay_at_hospital');
    $book_disease = sanitize($conn, $_POST['disease'] ?? $disease);

    if (!$slot_id)   $error = 'Please select a time slot.';
    elseif (!$pat_name) $error = 'Patient name is required.';
    elseif (!$age || $age < 1 || $age > 120) $error = 'Please enter a valid age.';
    elseif (!$gender) $error = 'Please select gender.';
    else {
        // Verify slot is still available
        $slotCheck = $conn->query("SELECT * FROM slots WHERE id=$slot_id AND doctor_id=$doctor_id AND is_booked=0");
        if (!$slotCheck->num_rows) {
            $error = 'Sorry, this slot was just booked. Please select another.';
        } else {
            $ref = generateRef();
            $user_id = $_SESSION['user_id'];
            $conn->query("INSERT INTO appointments (user_id, doctor_id, slot_id, patient_name, age, gender, height, weight, food_pref, illness_description, disease, payment_method, payment_status, booking_ref) VALUES ($user_id, $doctor_id, $slot_id, '$pat_name', $age, '$gender', $height, $weight, '$food_pref', '$illness', '$book_disease', '$pay_method', '".($pay_method==='online'?'paid':'pending')."', '$ref')");
            $appt_id = $conn->insert_id;
            $conn->query("UPDATE slots SET is_booked=1 WHERE id=$slot_id");
            redirect("confirmation.php?id=$appt_id");
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment – DSS Advanced Care</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-brand" href="index.php"><div class="brand-icon">⚕</div><div><div class="brand-name">DSS Advanced Care</div></div></a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="doctors.php" class="nav-link">Doctors</a>
            <a href="my_appointments.php" class="nav-link">My Appointments</a>
            <a href="logout.php" class="nav-btn-outline">Logout</a>
        </div>
    </div>
</nav>

<div class="booking-page">
    <div class="booking-container">
        <div class="booking-header">
            <a href="doctors.php" style="color:var(--gray-400);text-decoration:none;font-size:14px;">← Back to Doctors</a>
            <h1 style="margin-top:8px">Book Appointment</h1>
        </div>

        <?php if($error): ?><div class="alert alert-error" style="margin-bottom:24px">⚠️ <?= $error ?></div><?php endif; ?>

        <form method="POST" onsubmit="return validateBookingForm()">
            <input type="hidden" name="slot_id" id="selected_slot" value="">
            <input type="hidden" name="disease" value="<?= htmlspecialchars($disease) ?>">
            <input type="hidden" name="payment_method" id="payment_method" value="pay_at_hospital">

            <div class="booking-grid">
                <!-- LEFT COLUMN -->
                <div>
                    <!-- Slot Selection -->
                    <div class="booking-card" style="margin-bottom:24px">
                        <h3>📅 Select Date & Time</h3>
                        <div class="date-tabs">
                            <?php foreach($dates as $i => $date): ?>
                            <button type="button" class="date-tab <?= $i===0?'active':'' ?>" onclick="switchDate(this, '<?= $date ?>')">
                                <span class="dt-day"><?= date('d', strtotime($date)) ?></span>
                                <span class="dt-month"><?= date('M', strtotime($date)) ?></span>
                                <span style="font-size:10px;opacity:0.6"><?= date('D', strtotime($date)) ?></span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <?php foreach($dates as $i => $date): ?>
                        <div class="slot-group" id="slots-<?= $date ?>" style="display:<?= $i===0?'grid':'none' ?>;grid-template-columns:repeat(3,1fr);gap:10px">
                            <?php if(empty($slotData[$date])): ?>
                                <p style="grid-column:1/-1;text-align:center;color:var(--gray-400);padding:20px 0">No slots available for this date</p>
                            <?php else: ?>
                            <?php foreach($slotData[$date] as $slot): ?>
                            <button type="button"
                                class="slot-btn"
                                <?= $slot['is_booked'] ? 'disabled' : '' ?>
                                onclick="selectSlot(this, <?= $slot['id'] ?>)">
                                <?= htmlspecialchars($slot['slot_time']) ?>
                                <?php if($slot['is_booked']): ?><br><span style="font-size:10px;color:#aaa">Booked</span><?php endif; ?>
                            </button>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Patient Details -->
                    <div class="booking-card" style="margin-bottom:24px">
                        <h3>👤 Patient Information</h3>
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Patient's full name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Age *</label>
                                <input type="number" name="age" id="age" class="form-control" placeholder="Age in years" min="1" max="120" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Gender *</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" name="height" class="form-control" placeholder="e.g. 170" step="0.1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-control" placeholder="e.g. 65" step="0.1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Food Preference</label>
                            <select name="food_pref" class="form-control">
                                <option value="">Select Preference</option>
                                <option value="vegetarian">🌿 Vegetarian</option>
                                <option value="non-vegetarian">🍖 Non-Vegetarian</option>
                                <option value="vegan">🥦 Vegan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Describe Your Symptoms / Illness</label>
                            <textarea name="illness_description" class="form-control" rows="4" placeholder="Briefly describe your symptoms, medical history, or reason for visit..."><?= htmlspecialchars($_POST['illness_description']??$disease) ?></textarea>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="booking-card">
                        <h3>💳 Payment Method</h3>
                        <span class="dummy-badge">🔒 Secure Payment – Demo Mode</span>
                        <div class="payment-options">
                            <div class="payment-option selected" data-method="pay_at_hospital" onclick="selectPayment('pay_at_hospital')">
                                <div class="po-icon">🏥</div>
                                <div class="po-name">Pay at Hospital</div>
                                <div class="po-desc">Pay cash or card on arrival</div>
                            </div>
                            <div class="payment-option" data-method="online" onclick="selectPayment('online')">
                                <div class="po-icon">💳</div>
                                <div class="po-name">Online Payment</div>
                                <div class="po-desc">UPI / Card / Net Banking</div>
                            </div>
                        </div>
                        <div class="card-form" id="cardForm" style="display:none">
                            <h4>Card / UPI Details (Demo)</h4>
                            <div class="form-group">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" placeholder="4111 1111 1111 1111" maxlength="19" oninput="formatCard(this)">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123" maxlength="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">UPI ID (alternative)</label>
                                <input type="text" class="form-control" placeholder="yourname@upi">
                            </div>
                            <p style="font-size:12px;color:var(--gray-400);margin-top:8px">⚠️ This is a demo. No real payment will be processed.</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Doctor Summary -->
                <div>
                    <div class="doctor-summary">
                        <div style="width:80px;height:80px;background:rgba(255,255,255,0.2);border-radius:50%;overflow:hidden;margin-bottom:16px">
                            <?= getDoctorPortrait($doctor["id"],$doctor["name"]) ?>
                        </div>
                        <h4><?= htmlspecialchars($doctor['name']) ?></h4>
                        <div class="ds-spec"><?= htmlspecialchars($doctor['specialization']) ?></div>
                        <div class="ds-meta">
                            <span>⭐ <?= $doctor['rating'] ?> Rating</span>
                            <span>📅 <?= $doctor['experience'] ?> yrs exp</span>
                        </div>
                    </div>

                    <div class="booking-card" style="margin-bottom:16px">
                        <h3 style="font-size:15px;margin-bottom:16px">📋 Booking Summary</h3>
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--gray-200);font-size:13px">
                            <span style="color:var(--gray-400)">Consultation Fee</span>
                            <strong>₹<?= $doctor['fee'] ?></strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--gray-200);font-size:13px">
                            <span style="color:var(--gray-400)">Platform Fee</span>
                            <strong>₹0</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:14px 0;font-size:15px">
                            <span style="font-weight:700">Total</span>
                            <strong style="color:var(--primary)">₹<?= $doctor['fee'] ?></strong>
                        </div>
                    </div>

                    <div class="booking-card" style="margin-bottom:16px;background:#f0fff4;border-color:#c3f3d5">
                        <p style="font-size:13px;color:#27ae60">✅ <strong>Free cancellation</strong> up to 2 hours before appointment</p>
                    </div>

                    <button type="submit" class="btn-block" style="font-size:16px;padding:18px">
                        Confirm Booking →
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script>
function formatCard(input) {
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
}
</script>
</body>
</html>
