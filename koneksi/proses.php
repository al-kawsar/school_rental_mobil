<?php

require_once 'koneksi.php';
session_start(); // Mulai session di awal script

// Fungsi untuk validasi input
function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk redirect dengan SweetAlert
function redirectWithAlert($icon, $title, $text, $url, $buttonText = "OK")
{
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: "' . $icon . '",
            title: "' . $title . '",
            text: "' . $text . '",
            confirmButtonText: "' . $buttonText . '"
        }).then(() => {
            window.location = "' . $url . '";
        });
    });
    </script>';
    exit;
}

// Tambahkan token CSRF
// if (!isset($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }

// Proses login
if (isset($_GET['id']) && $_GET['id'] == 'login') {
    // Validasi CSRF token
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     redirectWithAlert("error", "Error!", "Invalid request", "../login.php");
    // }

    $user = validateInput($_POST['user']);
    $pass = validateInput($_POST['pass']);

    if (empty($user) || empty($pass)) {
        redirectWithAlert("error", "Login Gagal!", "Username dan password harus diisi", "../login.php", "Coba Lagi");
    }

    $row = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $row->execute([$user]);
    $hasil = $row->fetch();
    $hitung = $row->rowCount();
    if ($hitung > 0 && md5($pass) == $hasil['password']) {
        // Regenerate session ID untuk mencegah session fixation
        session_regenerate_id(true);
        $_SESSION['USER'] = $hasil;

        if ($_SESSION['USER']['level'] == 'admin') {
            redirectWithAlert("success", "Login Sukses!", "Anda berhasil masuk sebagai Admin.", "../admin/index.php");
        } else {
            redirectWithAlert("success", "Login Sukses!", "Selamat datang!", "../index.php");
        }
    } else {
        redirectWithAlert("error", "Login Gagal!", "Username atau password salah.", "../login.php", "Coba Lagi");
    }
}

// Proses pendaftaran
if (isset($_GET['id']) && $_GET['id'] == 'daftar') {
    // Validasi CSRF token
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     redirectWithAlert("error", "Error!", "Invalid request", "../index.php");
    // }

    $nama = validateInput($_POST['nama']);
    $user = validateInput($_POST['user']);
    $pass = validateInput($_POST['pass']);

    // Validasi data
    if (empty($nama) || empty($user) || empty($pass)) {
        redirectWithAlert("error", "Gagal!", "Semua field harus diisi", "../index.php");
    }

    // Validasi password strength
    if (strlen($pass) < 8) {
        redirectWithAlert("error", "Gagal!", "Password harus minimal 8 karakter", "../index.php");
    }

    // Cek username sudah digunakan atau belum
    $row = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $row->execute([$user]);
    $hitung = $row->rowCount();

    if ($hitung > 0) {
        redirectWithAlert("error", "Gagal!", "Daftar Gagal, Username sudah digunakan", "../index.php");
    } else {
        // Hash password dengan bcrypt
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users`(`nama_pengguna`, `username`, `password`, `level`) VALUES (?,?,?,?)";
        $row = $koneksi->prepare($sql);
        $row->execute([$nama, $user, $hashedPassword, 'pengguna']);

        redirectWithAlert("success", "Berhasil!", "Daftar Sukses, Silakan Login", "../index.php");
    }
}

// Proses booking
if (isset($_GET['id']) && $_GET['id'] == 'booking') {
    // Validasi CSRF token
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     redirectWithAlert("error", "Error!", "Invalid request", "../index.php");
    // }

    // Validasi user sudah login
    if (!isset($_SESSION['USER'])) {
        redirectWithAlert("error", "Error!", "Silakan login terlebih dahulu", "../login.php");
    }

    // Validasi input
    $id_user = (int) $_POST['id_user'];
    $id_mobil = (int) $_POST['id_mobil'];
    $ktp = validateInput($_POST['ktp']);
    $nama = validateInput($_POST['nama']);
    $alamat = validateInput($_POST['alamat']);
    $no_tlp = validateInput($_POST['no_tlp']);
    $tanggal = validateInput($_POST['tanggal']);
    $lama_sewa = (int) $_POST['lama_sewa'];
    $total_harga = (float) $_POST['total_harga'];

    // Validasi data
    if (empty($ktp) || empty($nama) || empty($alamat) || empty($no_tlp) || empty($tanggal) || $lama_sewa <= 0) {
        redirectWithAlert("error", "Gagal!", "Semua field harus diisi dengan benar", "../index.php");
    }

    // Generate kode booking yang lebih aman
    $kode_booking = uniqid('BK-', true);
    $unik = random_int(100, 999);
    $total_harga_final = ($total_harga * $lama_sewa) + $unik;

    $sql = "INSERT INTO booking (kode_booking, id_user, id_mobil, ktp, nama, alamat, no_tlp, tanggal, lama_sewa, total_harga, konfirmasi_pembayaran, tgl_input)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $row = $koneksi->prepare($sql);
    $row->execute([
        $kode_booking,
        $id_user,
        $id_mobil,
        $ktp,
        $nama,
        $alamat,
        $no_tlp,
        $tanggal,
        $lama_sewa,
        $total_harga_final,
        "Belum Bayar",
        date('Y-m-d')
    ]);

    redirectWithAlert("success", "Berhasil!", "Anda Sukses Booking, Silakan Melakukan Pembayaran", "../bayar.php?id=" . $kode_booking);
}

// Proses konfirmasi pembayaran
if (isset($_GET['id']) && $_GET['id'] == 'konfirmasi') {
    // Validasi CSRF token
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     redirectWithAlert("error", "Error!", "Invalid request", "../index.php");
    // }

    // Validasi user sudah login
    if (!isset($_SESSION['USER'])) {
        redirectWithAlert("error", "Error!", "Silakan login terlebih dahulu", "../login.php");
    }

    // Validasi input
    $id_booking = validateInput($_POST['id_booking']);
    $no_rekening = validateInput($_POST['no_rekening']);
    $nama = validateInput($_POST['nama']);
    $nominal = (float) $_POST['nominal'];
    $tgl = validateInput($_POST['tgl']);

    // Validasi data
    if (empty($id_booking) || empty($no_rekening) || empty($nama) || $nominal <= 0 || empty($tgl)) {
        redirectWithAlert("error", "Gagal!", "Semua field harus diisi dengan benar", "../index.php");
    }

    // Cek apakah booking ada dan milik user yang sedang login
    $check = $koneksi->prepare("SELECT * FROM booking WHERE id_booking = ? AND id_user = ?");
    $check->execute([$id_booking, $_SESSION['USER']['id_user']]);

    if ($check->rowCount() == 0) {
        redirectWithAlert("error", "Gagal!", "Data booking tidak ditemukan", "../index.php");
    }

    // Simpan konfirmasi pembayaran
    $sql = "INSERT INTO `pembayaran`(`id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) VALUES (?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    $row->execute([$id_booking, $no_rekening, $nama, $nominal, $tgl]);

    // Update status booking
    $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
    $row2 = $koneksi->prepare($sql2);
    $row2->execute(['Sedang di proses', $id_booking]);

    redirectWithAlert("success", "Kirim Sukses!", "Pembayaran Anda sedang diproses", "../booking.php");
}