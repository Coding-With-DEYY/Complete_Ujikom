<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "ujikomrpl2022_4";
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Periksa koneksi, tampilkan pesan jika gagal
if (!$link) {
    die("Koneksi dengan database gagal: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
}

?>