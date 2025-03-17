<?php
// Start session at the beginning
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa fa-user-circle me-2"></i>Login</h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="koneksi/proses.php?id=login" id="loginForm">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-user"></i></span>
                                <input type="text" name="user" id="username" class="form-control"
                                    placeholder="Masukkan username" required>
                            </div>
                            <div class="invalid-feedback username-feedback"></div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                                <input type="password" name="pass" id="password" class="form-control"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback password-feedback"></div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ingat saya</label>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fa fa-sign-in me-2"></i>Masuk
                            </button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun?
                                <a href="registrasi.php" class="text-primary fw-bold">
                                    Daftar sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    });
</script>