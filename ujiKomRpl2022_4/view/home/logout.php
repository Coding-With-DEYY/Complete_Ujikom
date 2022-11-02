<?php
session_start();

// hapus session
unset($_SESSION["nik"]);

// redirect ke halaman login.php
header("Location: login.php");
?>
