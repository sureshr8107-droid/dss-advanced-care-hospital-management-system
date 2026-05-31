<?php
// Shared header/navbar include for DSS Advanced Care
// Usage: include 'includes/header.php';

function adjustColor($hex, $amount) {
    $hex = ltrim($hex, '#');

    if (strlen($hex) == 3) {
        $hex =
            $hex[0].$hex[0].
            $hex[1].$hex[1].
            $hex[2].$hex[2];
    }

    $r = max(0, min(255, hexdec(substr($hex, 0, 2)) + $amount));
    $g = max(0, min(255, hexdec(substr($hex, 2, 2)) + $amount));
    $b = max(0, min(255, hexdec(substr($hex, 4, 2)) + $amount));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

function getDoctorPortrait($id, $name = '') {

    $colors = [
        1 => '#1a6fc4',
        2 => '#e74c3c',
        3 => '#27ae60',
        4 => '#8e44ad',
        5 => '#f39c12',
        6 => '#16a085',
        7 => '#d35400',
        8 => '#c0392b',
        9 => '#2c3e50',
        10 => '#3498db',
        11 => '#7f8c8d'
    ];

    $shirt = $colors[$id] ?? '#1a6fc4';
    $bg2   = adjustColor($shirt, -30);

    return '
    <svg viewBox="0 0 220 260" xmlns="http://www.w3.org/2000/svg" width="100%">
        <defs>
            <linearGradient id="bg'.$id.'" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="'.$shirt.'" stop-opacity="0.20"/>
                <stop offset="100%" stop-color="'.$bg2.'" stop-opacity="0.08"/>
            </linearGradient>

            <linearGradient id="coat'.$id.'" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#ffffff"/>
                <stop offset="100%" stop-color="#e8e8e8"/>
            </linearGradient>

            <linearGradient id="skin'.$id.'" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#f0c8a0"/>
                <stop offset="100%" stop-color="#d9a87f"/>
            </linearGradient>
        </defs>

        <!-- Background -->
        <rect width="220" height="260" fill="url(#bg'.$id.')"/>

        <!-- Body -->
        <path d="M40 150 L40 260 L180 260 L180 150 L150 130 L110 150 L70 130 Z"
              fill="url(#coat'.$id.')"/>

        <!-- Shirt -->
        <path d="M80 135 L110 160 L140 135 L140 260 L80 260 Z"
              fill="'.$shirt.'"/>

        <!-- Neck -->
        <rect x="97" y="110" width="26" height="28"
              fill="url(#skin'.$id.')"/>

        <!-- Face -->
        <ellipse cx="110" cy="78" rx="45" ry="50"
                 fill="url(#skin'.$id.')"/>

        <!-- Hair -->
        <ellipse cx="110" cy="52" rx="48" ry="28"
                 fill="#1b1b1b"/>

        <!-- Eyes -->
        <ellipse cx="92" cy="78" rx="6" ry="4" fill="white"/>
        <ellipse cx="128" cy="78" rx="6" ry="4" fill="white"/>

        <circle cx="92" cy="78" r="2.5" fill="#111"/>
        <circle cx="128" cy="78" r="2.5" fill="#111"/>

        <!-- Brows -->
        <path d="M84 68 Q92 64 100 68"
              stroke="#111" stroke-width="2" fill="none"/>
        <path d="M120 68 Q128 64 136 68"
              stroke="#111" stroke-width="2" fill="none"/>

        <!-- Nose -->
        <path d="M110 82 Q106 96 110 101"
              stroke="#b97e58" stroke-width="1.5" fill="none"/>

        <!-- Mouth -->
        <path d="M96 110 Q110 118 124 110"
              stroke="#b35c5c" stroke-width="2" fill="none"/>

        <!-- Stethoscope -->
        <path d="M86 155 Q70 170 75 190"
              stroke="#777" stroke-width="3" fill="none"/>

        <path d="M134 155 Q150 170 145 190"
              stroke="#777" stroke-width="3" fill="none"/>

        <circle cx="75" cy="194" r="8"
                fill="none" stroke="#777" stroke-width="3"/>

        <circle cx="145" cy="194" r="8"
                fill="none" stroke="#777" stroke-width="3"/>

        <!-- Badge -->
        <rect x="145" y="178" width="28" height="20"
              rx="3" fill="white" stroke="#ccc"/>

        <rect x="149" y="183" width="18" height="3"
              fill="'.$shirt.'"/>

        <rect x="149" y="189" width="12" height="2"
              fill="#bbb"/>
    </svg>';
}
?>

<!-- HEADER -->
<nav class="navbar">
    <div class="nav-container">

        <a href="index.php" class="nav-brand" style="text-decoration:none;">
            <div class="brand-icon">⚕</div>

            <div>
                <div class="brand-name">DSS Advanced Care</div>
                <div class="brand-tagline">
                    No.25, GST Road, Chennai - 600001
                </div>
                <div class="brand-tagline">
                    📞 +91 98765 43210
                </div>
            </div>
        </a>

        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="doctors.php" class="nav-link">Doctors</a>
            <a href="login.php" class="nav-link">Login</a>
            <a href="register.php" class="nav-btn">Register</a>
        </div>

    </div>
</nav>