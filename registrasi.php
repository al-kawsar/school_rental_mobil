<?php
// Start session at the beginning
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa fa-user-circle me-2"></i>Registrasi</h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="proses_registrasi.php" id="loginForm">
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-user"></i></span>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    value="<?php echo isset($error['nama']) ? $error['nama'] : ''; ?>"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="invalid-feedback nama-feedback">
                                <?php echo isset($error['nama']) ? $error['nama'] : ''; ?>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control"
                                    value="<?php echo isset($error['username']) ? $error['username'] : ''; ?>"
                                    placeholder="Masukkan username" required>
                            </div>
                            <div class="invalid-feedback username-feedback">
                                <?php echo isset($error['username']) ? $error['username'] : ''; ?>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback password-feedback">
                                <?php echo isset($error['password']) ? $error['password'] : ''; ?>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirm" class="form-label fw-bold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password_confirm" id="password_confirm"
                                    class="form-control"
                                    value="<?php echo isset($error['password_confirm']) ? $error['password_confirm'] : ''; ?>"
                                    placeholder="Konfirmasi password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                    <i class="fa fa-eye-slash" id="toggleIcon2"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback password-confirm-feedback">
                                <?php echo isset($error['password_confirm']) ? $error['password_confirm'] : ''; ?>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ingat saya</label>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fa fa-sign-in me-2"></i>Daftar
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-primary fw-bold">Login di
                                    sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
  document.addEventListener("DOMContentLoaded", function() {
      const togglePassword = document.querySelector("#togglePassword");
      const password = document.querySelector("#password");
      const icon = document.querySelector("#toggleIcon");
  
      const togglePassword2 = document.querySelector("#togglePassword2");
      const password2 = document.querySelector("#password_confirm");
      const icon2 = document.querySelector("#toggleIcon2");
  
      if (togglePassword && password && icon) {
          togglePassword.addEventListener("click", function () {
              const type = password.getAttribute("type") === "password" ? "text" : "password";
              password.setAttribute("type", type);
              icon.classList.toggle("fa-eye-slash");
              icon.classList.toggle("fa-eye");
          });
      }
  
      if (togglePassword2 && password2 && icon2) {
          togglePassword2.addEventListener("click", function () {
              const type = password2.getAttribute("type") === "password" ? "text" : "password";
              password2.setAttribute("type", type);
              icon2.classList.toggle("fa-eye-slash");
              icon2.classList.toggle("fa-eye");
          });
      }
  });
</script>