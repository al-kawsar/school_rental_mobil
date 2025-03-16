<?php
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';

// Security check - verify user is logged in
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

// Get car details with prepared statement for security
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $stmt = $koneksi->prepare("SELECT * FROM mobil WHERE id_mobil = ?");
                $stmt->execute([$id]);
                $isi = $stmt->fetch();

// Redirect if car doesn't exist
                if (!$isi) {
                    echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "Mobil Tidak Ditemukan",
                            text: "Mobil yang Anda cari tidak tersedia.",
                            confirmButtonText: "Kembali"
                            }).then(() => {
                                window.location = "index.php";
                                });
                                });
                                </script>';
                                exit;
                            }

// Calculate minimum date (today) and maximum date (6 months from now)
                            $today = date('Y-m-d');
                            $maxDate = date('Y-m-d', strtotime('+6 months'));
                            ?>

                            <div class="container py-5">
                                <div class="row">
                                    <div class="col-lg-4 mb-4">
                                        <div class="card shadow h-100">
                                            <div class="position-relative">
                                                <img src="assets/image/<?php echo htmlspecialchars($isi['gambar']); ?>" class="card-img-top"
                                                style="height: 250px; object-fit: cover;" alt="<?php echo htmlspecialchars($isi['merk']); ?>">
                                                <?php if($isi['status'] == 'Tersedia'): ?>
                                                    <span class="badge badge-pill badge-success position-absolute"
                                                    style="top: 10px; right: 10px; font-size: 14px; padding: 8px 15px;">
                                                    <i class="fa fa-check mr-1"></i> Tersedia
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-pill badge-danger position-absolute"
                                                style="top: 10px; right: 10px; font-size: 14px; padding: 8px 15px;">
                                                <i class="fa fa-times mr-1"></i> Tidak Tersedia
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body bg-light">
                                        <h4 class="card-title font-weight-bold"><?php echo htmlspecialchars($isi['merk']); ?></h4>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($isi['deskripsi']); ?></p>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-info text-white d-flex justify-content-between align-items-center">
                                            <span><i class="fa fa-tag mr-2"></i> Benefit</span>
                                            <span>Gratis E-toll 50k</span>
                                        </li>
                                        <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                                            <span><i class="fa fa-money mr-2"></i> Harga Sewa</span>
                                            <span class="font-weight-bold">Rp. <?php echo number_format($isi['harga']); ?> / hari</span>
                                        </li>
                                        <li class="list-group-item bg-primary text-white d-flex justify-content-between align-items-center">
                                            <span><i class="fa fa-id-card mr-2"></i> Nomor Plat</span>
                                            <span><?php echo htmlspecialchars($isi['no_plat']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="card shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fa fa-clipboard-list mr-2"></i> Form Pemesanan Mobil</h5>
                                    </div>

                                    <div class="card-body">
                                        <form method="post" action="koneksi/proses.php?id=booking" id="bookingForm" autocomplete="off" novalidate>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ktp"><strong>KTP / NIK</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                                            </div>
                                                            <input type="text" name="ktp" id="ktp" required class="form-control"
                                                            placeholder="Masukkan nomor KTP" pattern="[0-9]{16}" maxlength="16">
                                                            <div class="invalid-feedback">Nomor KTP harus 16 digit</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="nama"><strong>Nama Lengkap</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                            </div>
                                                            <input type="text" name="nama" id="nama" required class="form-control"
                                                            placeholder="Masukkan nama lengkap">
                                                            <div class="invalid-feedback">Nama lengkap diperlukan</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="alamat"><strong>Alamat</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-home"></i></span>
                                                            </div>
                                                            <textarea name="alamat" id="alamat" required class="form-control"
                                                            placeholder="Masukkan alamat lengkap" rows="3"></textarea>
                                                            <div class="invalid-feedback">Alamat diperlukan</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="no_tlp"><strong>Nomor Telepon</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                                            </div>
                                                            <input type="tel" name="no_tlp" id="no_tlp" required class="form-control"
                                                            placeholder="Masukkan nomor telepon" pattern="[0-9]{10,13}">
                                                            <div class="invalid-feedback">Nomor telepon tidak valid</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="tanggal"><strong>Tanggal Sewa</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                            <input type="date" name="tanggal" id="tanggal" required class="form-control"
                                                            min="<?php echo $today; ?>" max="<?php echo $maxDate; ?>">
                                                            <div class="invalid-feedback">Pilih tanggal yang valid</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="lama_sewa"><strong>Lama Sewa (Hari)</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                                            </div>
                                                            <input type="number" name="lama_sewa" id="lama_sewa" required class="form-control"
                                                            placeholder="Masukkan lama sewa" min="1" max="30">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">hari</span>
                                                            </div>
                                                            <div class="invalid-feedback">Minimal 1 hari, maksimal 30 hari</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="total_harga"><strong>Total Biaya</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" id="total_harga_display" class="form-control" readonly
                                                            value="<?php echo number_format($isi['harga']); ?>">
                                                        </div>
                                                        <small class="form-text text-muted">Total akan otomatis dihitung berdasarkan lama sewa</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Hidden inputs -->
                                            <input type="hidden" value="<?php echo intval($_SESSION['USER']['id_user']); ?>" name="id_user">
                                            <input type="hidden" value="<?php echo intval($isi['id_mobil']); ?>" name="id_mobil">
                                            <input type="hidden" value="<?php echo intval($isi['harga']); ?>" name="total_harga" id="total_harga">

                                            <hr>

                                            <div class="text-center">
                                                <?php if($isi['status'] == 'Tersedia'): ?>
                                                    <button type="submit" class="btn btn-primary btn-lg px-5" id="bookingBtn">
                                                        <i class="fa fa-check-circle mr-2"></i> Booking Sekarang
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-danger btn-lg px-5" disabled>
                                                        <i class="fa fa-times-circle mr-2"></i> Mobil Tidak Tersedia
                                                    </button>
                                                    <p class="text-muted mt-2">Silakan pilih mobil lain yang tersedia</p>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
    // Form validation
                            const form = document.getElementById('bookingForm');
                            form.addEventListener('submit', function(event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                } else {
            // Disable submit button to prevent double submissions
                                    document.getElementById('bookingBtn').disabled = true;
                                    document.getElementById('bookingBtn').innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Memproses...';
                                }
                                form.classList.add('was-validated');
                            }, false);

    // Auto-calculate total price
                            const pricePerDay = <?php echo intval($isi['harga']); ?>;
                            const lamaSewa = document.getElementById('lama_sewa');
                            const totalHarga = document.getElementById('total_harga');
                            const totalHargaDisplay = document.getElementById('total_harga_display');

                            lamaSewa.addEventListener('input', function() {
                                const days = parseInt(this.value) || 1;
                                const total = pricePerDay * days;
                                totalHarga.value = total;
                                totalHargaDisplay.value = new Intl.NumberFormat('id-ID').format(total);
                            });

    // Input validation
                            const ktpInput = document.getElementById('ktp');
                            ktpInput.addEventListener('input', function() {
                                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                            });

                            const phoneInput = document.getElementById('no_tlp');
                            phoneInput.addEventListener('input', function() {
                                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
                            });
                        });
                    </script>

                    <?php include 'footer.php'; ?>