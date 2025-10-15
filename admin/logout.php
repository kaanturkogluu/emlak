<?php
session_start();

// Oturumu sonlandır
session_destroy();

// Login sayfasına yönlendir
header('Location: login.php');
exit;
?>
