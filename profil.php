<?php
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';

if (empty($_SESSION['USER'])) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: "warning",
            title: "Harap Login!",
            text: "Anda harus login untuk mengakses halaman ini.",
            showCancelButton: true,
            confirmButtonText: "OK",
            cancelButtonText: "Login"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "index.php"; // Jika OK, kembali ke halaman utama
            } else {
                window.location = "login.php"; // Jika Login, ke halaman login
            }
        });
    });
    </script>';
    exit;
}

if (!empty($_POST['nama_pengguna'])) {
    $data[] = htmlspecialchars($_POST["nama_pengguna"]);
    $data[] = htmlspecialchars($_POST["username"]);

    // Only update password if it's not empty
    if (!empty($_POST["password"])) {
        $sql = "UPDATE users SET nama_pengguna = ?, username = ?, password = ? WHERE id_user = ?";
        $data[] = md5($_POST["password"]);
        $data[] = $_SESSION['USER']['id_user'];
    } else {
        $sql = "UPDATE users SET nama_pengguna = ?, username = ? WHERE id_user = ?";
        $data[] = $_SESSION['USER']['id_user'];
    }

    $row = $koneksi->prepare($sql);
    $execute = $row->execute($data);

    if ($execute) {
        $_SESSION['USER']['nama_pengguna'] = htmlspecialchars($_POST["nama_pengguna"]);
        $_SESSION['USER']['username'] = htmlspecialchars($_POST["username"]);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Update Data Profil Berhasil!',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location='profil.php';
        });
        </script>";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat mengupdate data.',
            showConfirmButton: false,
            timer: 2000
        });
        </script>";
    }
}

// Get user data
$id = $_SESSION["USER"]["id_user"];
$sql = "SELECT * FROM users WHERE id_user = ?";
$row = $koneksi->prepare($sql);
$row->execute(array($id));
$edit_profil = $row->fetch(PDO::FETCH_OBJ);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit mr-2"></i> Edit Profil</h4>
                </div>
                <div class="card-body p-4">
                    <form action="" method="post" id="profileForm">
                        <div class="form-group row mb-4">
                            <label for="nama_pengguna" class="col-sm-3 col-form-label">Nama Pengguna</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($edit_profil->nama_pengguna); ?>" name="nama_pengguna"
                                    id="nama_pengguna" required />
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($edit_profil->username); ?>" name="username"
                                    id="username" required />
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Kosongkan jika tidak ingin mengubah password" />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah
                                    password</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="index.php" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this before closing body tag -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Form validation
        const form = document.getElementById('profileForm');
        form.addEventListener('submit', function (event) {
            const nama = document.getElementById('nama_pengguna').value.trim();
            const username = document.getElementById('username').value.trim();

            if (nama === '' || username === '') {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Nama pengguna dan username tidak boleh kosong!'
                });
            }
        });
    });
</script>