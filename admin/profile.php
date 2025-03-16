<?php
require_once '../koneksi/koneksi.php';
$title_web = 'Profile';
include 'header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}



// Process admin profile form submission
if(!empty($_POST['nama_pengguna'])) {
    $data[] = htmlspecialchars($_POST["nama_pengguna"]);
    $data[] = htmlspecialchars($_POST["username"]);
    $data[] = md5($_POST["password"]);
    $data[] = $_SESSION['USER']['id_user'];
    $sql = "UPDATE users SET nama_pengguna = ?, username = ?, password = ? WHERE id_user = ?";
    $row = $koneksi->prepare($sql);
    $row->execute($data);
    echo '<script>
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "Update Data Profil Berhasil!",
        showConfirmButton: false,
        timer: 1500
        }).then(function() {
            window.location.href = `profile.php`;
            });
            </script>';
        }

// Fetch admin profile data
        $id = $_SESSION["USER"]["id_login"];
        $sql = "SELECT * FROM users WHERE id_user = ?";
        $row = $koneksi->prepare($sql);
        $row->execute(array($id));
        $edit_profil = $row->fetch(PDO::FETCH_OBJ);
        ?>


<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<div class="container mt-4">


    <div class="row">
        <!-- Admin Profile Card -->
        <div class="col-12 ">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i> Profil Admin</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="formProfile">
                        <div class="text-center mb-4">
                            <div class="avatar-circle mx-auto">
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            </div>
                            <h5 class="mt-3 mb-0"><?= htmlspecialchars($edit_profil->nama_pengguna); ?></h5>
                            <p class="text-muted">Administrator</p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_pengguna" class="form-label fw-bold">Nama Pengguna</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($edit_profil->nama_pengguna); ?>" name="nama_pengguna" id="nama_pengguna" placeholder="Masukkan nama pengguna" required/>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($edit_profil->username); ?>" name="username" id="username" placeholder="Masukkan username" required/>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru" required/>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <small class="text-muted">Masukkan password baru untuk mengubah password Anda</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }
    .card-header {
        border-bottom: none;
        padding: 15px 20px;
    }
    .form-label {
        margin-bottom: 0.3rem;
    }
    .input-group-text {
        background-color: #f8f9fa;
        width: 40px;
        justify-content: center;
    }
    .avatar-circle {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f8f9fa;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

        // Toggle the eye / eye-slash icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });


    // Form validation for Admin Profile
        const formProfile = document.getElementById('formProfile');
        formProfile.addEventListener('submit', function(event) {
            if (!formProfile.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Harap isi semua field yang diperlukan!',
                });
            }
            formProfile.classList.add('was-validated');
        });
    });
</script>

<?php include 'footer.php'; ?>