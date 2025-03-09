<?php
// Start session at the beginning
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

// Fetch cars once and cache the result
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6; // Number of cars per page
$offset = ($page - 1) * $per_page;

// Count total cars for pagination
$count_query = $koneksi->query('SELECT COUNT(*) as total FROM mobil');
$total_cars = $count_query->fetch()['total'];
$total_pages = ceil($total_cars / $per_page);

// Get cars with pagination
$querymobil = $koneksi->query("SELECT * FROM mobil ORDER BY id_mobil DESC LIMIT $offset, $per_page")->fetchAll();
// Get featured cars for carousel (limit to 5)
$featured_cars = $koneksi->query('SELECT * FROM mobil WHERE status = "Tersedia" ORDER BY id_mobil DESC LIMIT 5')->fetchAll();
?>

<!-- Improved Carousel with Lazy Loading -->
<div id="carouselId" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
        $no = 0;
        foreach($featured_cars as $isi) {
            echo '<li data-target="#carouselId" data-slide-to="'.$no.'" class="'.($no == 0 ? 'active' : '').'"></li>';
            $no++;
        }
        ?>
    </ol>
    <div class="carousel-inner shadow-sm rounded" role="listbox">
        <?php
        $no = 0;
        foreach($featured_cars as $isi) {
            ?>
            <div class="carousel-item <?= ($no == 0) ? 'active' : ''; ?>">
                <img data-src="assets/image/<?= htmlspecialchars($isi['gambar']); ?>"
                src="assets/image/placeholder.jpg"
                class="img-fluid lazy-load w-100"
                alt="<?= htmlspecialchars($isi['merk']); ?>"
                style="object-fit:cover; height:60vh; max-height:500px;">
                <div class="carousel-caption d-none d-md-block">
                    <h3 class="bg-dark bg-opacity-50 p-2 rounded"><?= htmlspecialchars($isi['merk']); ?></h3>
                    <p class="bg-dark bg-opacity-50 p-2 rounded">Rp. <?= number_format($isi['harga']); ?>/hari</p>
                </div>
            </div>
            <?php $no++; } ?>
        </div>
        <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container mt-5">
        <div class="row">
            <!-- Improved Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <?= isset($_SESSION['USER']) ? 'Akun Saya' : 'Login Akun'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['USER'])) { ?>
                            <div class="text-center mb-3">
                                <i class="fa fa-user-circle fa-4x text-primary mb-2"></i>
                                <h5>Selamat Datang, <?= htmlspecialchars($_SESSION['USER']['nama_pengguna']); ?></h5>
                                <p class="text-muted small">Anda login sebagai <?= $_SESSION['USER']['level']; ?></p>
                            </div>
                            <div class="d-grid gap-2">
                                <?php if($_SESSION['USER']['level'] == 'admin') { ?>
                                    <a href="admin/index.php" class="btn btn-primary btn-block">
                                        <i class="fa fa-dashboard"></i> Dashboard Admin
                                    </a>
                                <?php } else { ?>
                                    <a href="blog.php" class="btn btn-success btn-block">
                                        <i class="fa fa-car"></i> Booking Sekarang!
                                    </a>
                                    <a href="history.php" class="btn btn-info text-white btn-block">
                                        <i class="fa fa-history"></i> Riwayat Booking
                                    </a>
                                <?php } ?>
                                <a href="admin/logout.php" class="btn btn-danger text-white btn-block">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </div>
                        <?php } else { ?>
                            <form method="post" action="koneksi/proses.php?id=login" id="loginForm">
                                <div class="form-group mb-3">
                                    <label for="username">Username</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        </div>
                                        <input type="text" name="user" id="username" class="form-control" placeholder="Username" required>
                                    </div>
                                    <div class="invalid-feedback username-feedback"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="pass" id="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="invalid-feedback password-feedback"></div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-sign-in"></i> Login
                                    </button>
                                    <a class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#registerModal">
                                        <i class="fa fa-user-plus"></i> Daftar
                                    </a>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>

                <!-- Search and Filter Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Cari Mobil</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="get">
                            <div class="form-group mb-3">
                                <label for="search">Kata Kunci</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Merk atau tipe mobil">
                            </div>
                            <div class="form-group mb-3">
                                <label for="price">Harga Maksimum</label>
                                <select name="price" id="price" class="form-control">
                                    <option value="">Semua Harga</option>
                                    <option value="300000">< Rp. 300.000/hari</option>
                                    <option value="500000">< Rp. 500.000/hari</option>
                                    <option value="1000000">< Rp. 1.000.000/hari</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="available" name="available" value="1">
                                    <label class="custom-control-label" for="available">Hanya Tersedia</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Improved Car Listing -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Daftar Mobil</h2>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary active" id="grid-view">
                            <i class="fa fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="list-view">
                            <i class="fa fa-list"></i>
                        </button>
                    </div>
                </div>

                <div class="row" id="car-list">
                    <?php
                    if (count($querymobil) > 0) {
                        foreach($querymobil as $isi) {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm hover-card">
                                    <div class="position-relative">
                                        <img src="assets/image/<?= htmlspecialchars($isi['gambar']); ?>"
                                        class="card-img-top"
                                        alt="<?= htmlspecialchars($isi['merk']); ?>"
                                        style="height:200px; object-fit: cover;">
                                        <?php if($isi['status'] == 'Tersedia') { ?>
                                            <span class="badge badge-success position-absolute" style="top:10px; right:10px;">
                                                <i class="fa fa-check"></i> Tersedia
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger position-absolute" style="top:10px; right:10px;">
                                                <i class="fa fa-times"></i> Tidak Tersedia
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($isi['merk']); ?></h5>
                                        <p class="card-text text-muted small">
                                            <i class="fa fa-info-circle"></i> <?= substr(htmlspecialchars($isi['deskripsi'] ?? ''), 0, 50); ?>...
                                        </p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-light">
                                            <i class="fa fa-check text-success"></i> Gratis E-toll 50k
                                        </li>
                                        <li class="list-group-item bg-light">
                                            <i class="fa fa-money text-primary"></i> Rp. <?= number_format($isi['harga']); ?>/ hari
                                        </li>
                                    </ul>
                                    <div class="card-body bg-light">
                                        <div class="d-flex justify-content-between">
                                            <a href="detail.php?id=<?= $isi['id_mobil']; ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-info-circle"></i> Detail
                                            </a>
                                            <a href="booking.php?id=<?= $isi['id_mobil']; ?>" class="btn btn-success btn-sm <?= ($isi['status'] != 'Tersedia') ? 'disabled' : ''; ?>">
                                                <i class="fa fa-calendar-check-o"></i> Booking
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><div class="alert alert-info">Tidak ada mobil yang tersedia saat ini</div></div>';
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page-1; ?>" tabindex="-1">Previous</a>
                            </li>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page+1; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

<!-- Improved Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registerModalTitle">Daftar Akun Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="koneksi/proses.php?id=daftar" id="registerForm">
                    <div class="form-group mb-3">
                        <label for="nama">Nama Lengkap</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="invalid-feedback nama-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="user_reg">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                            </div>
                            <input type="text" name="user" id="user_reg" class="form-control" required>
                        </div>
                        <div class="invalid-feedback user-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pass_reg">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="pass" id="pass_reg" class="form-control" required>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar" id="password-strength" style="width: 0%"></div>
                        </div>
                        <small class="form-text text-muted">Password harus minimal 8 karakter dan mengandung huruf dan angka</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pass_confirm">Konfirmasi Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="pass_confirm" id="pass_confirm" class="form-control" required>
                        </div>
                        <div class="invalid-feedback pass-confirm-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" form="registerForm" class="btn btn-primary">Daftar Sekarang</button>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript for form validation and image lazy loading -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading for images
        const lazyImages = document.querySelectorAll('.lazy-load');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy-load');
                        imageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        }

    // Password strength checker
        const passField = document.getElementById('pass_reg');
        const strengthBar = document.getElementById('password-strength');

        if (passField && strengthBar) {
            passField.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/)) strength += 25;

                strengthBar.style.width = strength + '%';

                if (strength < 50) {
                    strengthBar.className = 'progress-bar bg-danger';
                } else if (strength < 75) {
                    strengthBar.className = 'progress-bar bg-warning';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                }
            });
        }

    // Toggle between grid and list views
        const gridBtn = document.getElementById('grid-view');
        const listBtn = document.getElementById('list-view');
        const carList = document.getElementById('car-list');

        if (gridBtn && listBtn && carList) {
            gridBtn.addEventListener('click', function() {
                carList.className = 'row';
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');

                document.querySelectorAll('#car-list > div').forEach(item => {
                    item.className = 'col-md-4 mb-4';
                });
            });

            listBtn.addEventListener('click', function() {
                carList.className = 'row list-view';
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');

                document.querySelectorAll('#car-list > div').forEach(item => {
                    item.className = 'col-12 mb-3';
                });
            });
        }

    // Form validation
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                let valid = true;

                const pass = document.getElementById('pass_reg').value;
                const confirmPass = document.getElementById('pass_confirm').value;

                if (pass !== confirmPass) {
                    document.querySelector('.pass-confirm-feedback').textContent = 'Password tidak sama';
                    document.getElementById('pass_confirm').classList.add('is-invalid');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }
    });
</script>

<style>
/* Add hover effects to cards */
.hover-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Style for list view */
.list-view .card {
    flex-direction: row;
    overflow: hidden;
}
.list-view .card-img-top {
    width: 30%;
    height: 100%;
}
.list-view .card-body {
    width: 40%;
}
.list-view .list-group {
    width: 30%;
    display: flex;
    flex-direction: column;
}
.list-view .card-body:last-child {
    border-top: none;
    border-left: 1px solid rgba(0,0,0,.125);
}

/* Improve responsive behavior */
@media (max-width: 768px) {
    .list-view .card {
        flex-direction: column;
    }
    .list-view .card-img-top,
    .list-view .card-body,
    .list-view .list-group {
        width: 100%;
    }
}
</style>

<?php
include 'footer.php';
?>