<?php
// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
if (isset($_POST["submit"])) {

    // form telah disubmit, proses data

    // ambil nilai form
    $nik = htmlentities(strip_tags(trim($_POST["nik"])));
    $nama_lengkap = htmlentities(strip_tags(trim($_POST["nama_lengkap"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek apakah "nik" sudah diisi atau tidak
    if (empty($nik)) {
        $pesan_error .= "NIK belum diisi <br>";
    }

    // cek apakah "nama_lengkap" sudah diisi atau tidak
    if (empty($nama_lengkap)) {
        $pesan_error .= "Nama lengkap belum diisi <br>";
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

    // filter dengan mysqli_real_escape_string
    $nik = mysqli_real_escape_string($link, $nik);
    $nama_lengkap = mysqli_real_escape_string($link,$nama_lengkap);

    // cek apakah nik dan nama_lengkap ada di tabel pengguna
    $query = "SELECT * FROM pengguna WHERE nik = '$nik' AND nama_lengkap = '$nama_lengkap'";
    $result = mysqli_query($link, $query);

    $rows = mysqli_fetch_row($result);
    $count = mysqli_num_rows($result);


//    echo "<pre>";
//    print_r($count);
//    print_r($rows[0]);
//    echo "</pre>";

    if (mysqli_num_rows($result) == 0) {
        // data tidak ditemukan, buat pesan error
        $pesan_error .= "NIK dan/atau nama lengkap tidak sesuai";
    }

    // bebaskan memory
    mysqli_free_result($result);

    // tutup koneksi dengan database MySQL
    mysqli_close($link);

    // jika lolos validasi, set session
    if ($pesan_error === "") {
        session_start();
        $_SESSION["nik"] = $nik;
        header("Location: home.php");
    }
} else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $nik = "";
    $nama_lengkap = "";
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

    <title>Login | Peduli Diri</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        main.main_deg {
            padding: 45px;
        }

        .h3_deg {
            text-align: center;
            font-family: sans-serif;
        }

        a.text_link {
            text-decoration: none;
        }

        .error {
            background-color: #FFECEC;
            padding: 20px;
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <main class="main_deg ml-5 mr-5">
        <div class="card">
            <div class="card-body">
                <h3 class="h3_deg">Log In</h3>

                <?php
                // tampilkan pesan jika ada
                if (isset($pesan)) {
                    echo "<div class='pesan'>$pesan</div>";
                }

                // tampilkan error jika ada
                if ($pesan_error !== "") {
                    echo "<div class='error'>$pesan_error</div>";
                }
                ?>

                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="number" class="form-control" id="nik" name="nik" value="<?php echo $nik?>">
                        <small id="nik" class="form-text text-muted">Nomor NIK tidak boleh kurang atau lebih dari 16 digit.</small>
                    </div>
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukan Nama Anda" value="<?php echo $nama_lengkap?>">
                    </div>
                    <a href="register.php" class="btn btn-info">Register</a>
                    <input type="submit" name="submit" class="btn btn-success" value="Login">
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>