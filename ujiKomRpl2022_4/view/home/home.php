<?php
// periksa apakah user sudah login, cek kehadiran session nik
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nik"])) {
    header("Location: login.php");;
}

// buka koneksi ke mysql
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "ujikomrpl2022_4";
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// periksa koneksi, tampilkan pesan kesalahan jika gagal
if (!$link) {
    die("Koneksi dengan database gagal : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Peduli Diri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        ul.nav li.nav-item a.text_deg:hover {
            color: #000 !important;
        }

        table.table_deg {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<header class="container-fluid mt-5 ml-5 pt-3 pl-5">
    <div class="row">
        <div class="col-2">
            <?php
                $nik = mysqli_real_escape_string($link, $_SESSION["nik"]);
                $result = mysqli_query($link, "SELECT * FROM pengguna WHERE nik = $nik");

                if (!$result) {
                    die("Query Error: ".mysqli_errno($link)." - ".mysqli_error($link));
                }

                $data = mysqli_fetch_array($result);
            ?>
            <img src="uploads/<?php echo $data['photo']?>" alt="..." class="img-thumbnail">
            <h4 style="text-align: center;"><?php echo $data['nama_lengkap'];?></h4>
        </div>
        <div class="col-10">
            <h1>Peduli Diri</h1>
            <p>Catatan Perjalanan</p>
            <ul class="nav">
                <li class="nav-item ml-0 pl-0">
                    <a style="display: inline;" class="nav-link text_deg" href="home.php">Home</a>
                </li>
                <li class="nav-item ml-0 pl-0">
                    <span>|</span> <a style="display: inline;" class="nav-link text_deg" href="catatan_perjalanan.php">Catatan Perjalanan </a>
                </li>
                <li class="nav-item ml-0 pl-0">
                    <span>|</span> <a style="display: inline;" class="nav-link text_deg" href="isi_data.php">Isi Data</a>
                </li>
                <li class="nav-item ml-0 pl-0">
                    <span>|</span> <a style="display: inline;" class="nav-link text_deg" href="logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<article class="container-fluid pb-5 mb-5">
    <main class="mt-5 pt-3">
        <div class="border border-dark" style="margin-top: 25px; margin-right: 15%; margin-left: 15%;">
            <p style="margin-top: 20px; margin-left: 20px;">Selamat datang <span><?php echo $data['nama_lengkap'];?></span> di aplikasi Peduli Diri</p>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
        <br>
        <a href="isi_data.php" class="btn btn-primary" style="margin-left: 72%; margin-top: 15px;">Isi Catatan Perjalanan</a>
    </main>
</article>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>