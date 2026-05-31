<?php
require_once 'config.php';
session_destroy();
redirect('login.php?msg=You+have+been+logged+out+successfully.');
?>
