<?php
require_once 'config.php';
unset($_SESSION['doctor_id'], $_SESSION['doctor_name'], $_SESSION['doctor_spec']);
redirect('doctor_login.php');
?>
