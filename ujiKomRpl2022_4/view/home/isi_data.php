<?php
// periksa apakah user sudah login, cek kehadiran session nik
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nik"])) {
    header("Location: login.php");;
}

//    echo "<pre>";
//    print_r($_SESSION["nik"]);
//    echo "</pre>";


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

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil semua nilai dari form
    $tanggal = htmlentities(strip_tags(trim($_POST["tanggal"])));
    $waktu = htmlentities(strip_tags(trim($_POST["waktu"])));
    $lokasi = htmlentities(strip_tags(trim($_POST["lokasi"])));
    $suhu_tubuh = htmlentities(strip_tags(trim($_POST["suhu_tubuh"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek apakah tanggal sudah diisi atau tidak
    if (empty($tanggal)) {
        $pesan_error .= "Tanggal belum diisi <br>";
    }

    // cek apakah waktu sudah diisi atau tidak
    if (empty($waktu)) {
        $pesan_error .= "Waktu belum diisi <br>";
    }

    // cek apakah lokasi sudah diisi atau tidak
    if (empty($lokasi)) {
        $pesan_error .= "Lokasi belum diisi <br>";
    }

    // cek apakah suhu_tubuh sudah diisi atau tidak
    if (empty($suhu_tubuh)) {
        $pesan_error .= "Suhu tubuh belum diisi <br>";
    }

    // jika tidak ada error, input ke database
    if ($pesan_error === "") {

        // filter semua data
        $tanggal = mysqli_real_escape_string($link, $tanggal);
        $waktu = mysqli_real_escape_string($link, $waktu);
        $lokasi = mysqli_real_escape_string($link, $lokasi);
        $suhu_tubuh = mysqli_real_escape_string($link, $suhu_tubuh);
        $nik = mysqli_real_escape_string($link, $_SESSION["nik"]);

        // buat dan jalankan query INSERT
        $query = "INSERT INTO data_perjalanan VALUES (null, '$nik', '$tanggal', '$waktu', '$lokasi', '$suhu_tubuh')";
        $result = mysqli_query($link, $query);

        // periksa hasil query
        if ($result) {
            // INSERT berhasil, redirect ke catatan_perjalanan.php + pesan
            $pesan = "Data perjalanan sudah berhasil di simpan";
            $pesan = urlencode($pesan);
            header("Location: catatan_perjalanan.php?pesan={$pesan} ");
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $tanggal = "";
    $waktu = "";
    $lokasi = "";
    $suhu_tubuh = "";
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
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <?php
                        // tampilkan error jika ada
                        if ($pesan_error !== "") {
                            echo "<div class='error'>$pesan_error</div>";
                        }
                        ?>
                        <form id="form-data_perjalanan" action="isi_data.php" method="post">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php $tanggal ?>">
                            </div>
                            <div class="form-group">
                                <label for="waktu">Jam</label>
                                <input type="time" class="form-control" id="waktu" name="waktu" value="<?php $waktu ?>">
                            </div>
                            <div class="form-group">
                                <label for="lokasi">Lokasi yang dikunjungi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php $lokasi ?>">
                            </div>
                            <div class="form-group">
                                <label for="suhu_tubuh">Suhu Tubuh</label>
                                <input type="text" class="form-control" id="suhu_tubuh" name="suhu_tubuh" value="<?php $suhu_tubuh ?>">
                            </div>
                            <input type="submit" class="btn btn-primary" name="submit" value="Simpan" style="margin-left: 91%; margin-top: 15px;">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </main>
</article>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

<?php
// tutup koneksi dengan database mysql
mysqli_close($link);
?>