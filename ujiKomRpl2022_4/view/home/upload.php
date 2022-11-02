<?php
	if (isset($_POST['register']) && isset($_FILES['foto'])) {
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

	    $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_STRING);
	    $fullName = filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING);
	    $nik = filter_input(INPUT_POST, 'nik', FILTER_SANITIZE_NUMBER_INT);

	    $pesan_error = "";

	    // enskripsi nik
	    if (empty($nik)) {
	        $pesan_error .= "NIK Belum diisi <br>";
	    } else if (is_numeric($nik)) {
	        $pesan_error .= "NIK harus berupa angka <br>";
	    } else if (strlen($nik) > 16 && strlen($nik) < 16) {
	        $pesan_error .= "NIK harus 16 digit angka <br>";
	        return;
	    }

	    if (empty($fullName)) {
	        $pesan_error .= "Nama lengkap belum diisi <br>";
	    }

	    // enskripsi foto
	    if (empty($foto)) {
	        $pesan_error .= "Foto belum upload <br>";
	    }

	    if (!$nik || !$fullName || !$foto) {
	        $pesan_error .= "Data tidak boleh kosong <br>";
	    } else {
	        $nik = mysqli_real_escape_string($link, $nik);
	        $query = "SELECT * FROM pengguna WHERE nik='$nik'";
	        $hasil_query = mysqli_query($link, $query);

	        $jumlah_data = mysqli_num_rows($hasil_query);
	        if ($jumlah_data >= 1) {
	            $pesan_error .= "NIK yang sama sudah digunakan <br>";
	            return;
	        }

	        // menyiapkan query
	        $sql = "INSERT INTO pengguna VALUES (:nik, :nama_lengkap, :foto)";
	        $stmt = $link->prepare($sql);

	        // bind parameter ke query
	        $param = array(
	            ":nik" => $nik,
	            ":nama_lengkap" => $fullName,
	            ":foto" => $foto
	        );

	        // eksekusi query untuk menyimpan ke database
	        $saved = $stmt->execute($param);

	        // jika query simpan berhasil, maka user sudah terdaftar
	        // maka alihkan ke halaman login
	        if ($saved) header("Location: login.php");
	    }

		echo "<pre>";
		print_r($_FILES['foto']);
		echo "</pre>";

		$img_name = $_FILES['foto']['name'];
		$img_size = $_FILES['foto']['size'];
		$tmp_name = $_FILES['foto']['tmp_name'];
		$error = $_FILES['foto']['error'];

		if ($error == 0) {
			if ($img_size > 125000) {
				$em = "Sorry, your file is too large.";
				header("Location: register.php?error=$em");
			}
			else {
				$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ex_lc = strtolower($img_ex);
				$allowed_exs = array("jpg","jpeg","png");

				if (in_array($img_ex_lc, $allowed_exs)) {
					$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
					$img_upload_path = 'uploads/'.$new_img_name;
					move_uploaded_file($tmp_name, $img_upload_path);

					$query = "INSERT INTO pengguna(nik, nama_lengkap, photo) VALUES('$nik','$fullName','$new_img_name')";
					mysqli_query($link, $query);
					header("Location: login.php");
				}
				else {
					$em = "You can't upload files of this type";
					header("Location: register.php?error=$em");
				}
			}
		}
		else {
			$em = "Unknown error occured!";
			header("Location: register.php?error=$em");
		}
	}
	else {
		header("Location: login.php");
	}
?>