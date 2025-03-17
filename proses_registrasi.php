<?php
include 'koneksi/koneksi.php';

echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_pengguna = htmlspecialchars(trim($_POST['nama']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password_confirm = htmlspecialchars(trim($_POST['password_confirm']));

    // Validasi konfirmasi password
    if ($password != $password_confirm) {
        echo "
        <script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Konfirmasi password tidak sesuai!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'registrasi.php';
            });
        });
        </script>";
        exit;
    }

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        echo "
        <script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Username dan password tidak boleh kosong!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'registrasi.php';
            });
        });
        </script>";
    }

    // Cek apakah username sudah digunakan
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Username sudah digunakan!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'registrasi.php';
                });
            });
        </script>";
    }

    // Hash password dengan password_hash
    $hashed_password = md5($password);

    // Insert data ke database
    $insert = $koneksi->prepare("INSERT INTO users (nama_pengguna, username, password, level) VALUES (:nama_pengguna, :username, :password, 'pengguna')");
    $insert->bindParam(':nama_pengguna', $nama_pengguna);
    $insert->bindParam(':username', $username);
    $insert->bindParam(':password', $hashed_password);
    if ($insert->execute()) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Registrasi berhasil!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>";
    } else {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Registrasi gagal, coba lagi nanti.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'registrasi.php';
                });
            });
        </script>";
    }
}

