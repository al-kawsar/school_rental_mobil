<?php
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $koneksi->prepare("SELECT * FROM mobil WHERE id_mobil = ?");
$stmt->execute([$id]);
$hasil = $stmt->fetch();

// Check if car exists
if (!$hasil) {
    // Redirect to index if car not found
    header('Location: index.php');
    exit;
}

$stmt = $koneksi->prepare("SELECT * FROM mobil WHERE id_mobil != ? AND status = 'Tersedia' LIMIT 3");
$stmt->execute([$id]);
$related_cars = $stmt->fetchAll();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-3">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($hasil['merk']); ?></li>
                </ol>
            </nav>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div id="carDetailSlider" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carDetailSlider" data-slide-to="0" class="active"></li>
                            <li data-target="#carDetailSlider" data-slide-to="1"></li>
                            <li data-target="#carDetailSlider" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" style="height: 400px; object-fit: cover;"
                                src="assets/image/<?= htmlspecialchars($hasil['gambar']); ?>"
                                alt="<?= htmlspecialchars($hasil['merk']); ?>">
                            </div>
                            <!-- Placeholder images if no additional images available -->
                            <div class="carousel-item">
                                <img class="d-block w-100" style="height: 400px; object-fit: cover;"
                                src="assets/image/<?= htmlspecialchars($hasil['gambar']); ?>"
                                alt="<?= htmlspecialchars($hasil['merk']); ?> - Interior">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 class="bg-dark bg-opacity-50 p-2 rounded">Interior View</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" style="height: 400px; object-fit: cover;"
                                src="assets/image/<?= htmlspecialchars($hasil['gambar']); ?>"
                                alt="<?= htmlspecialchars($hasil['merk']); ?> - Rear View">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 class="bg-dark bg-opacity-50 p-2 rounded">Rear View</h5>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carDetailSlider" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carDetailSlider" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Car Details Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><?= htmlspecialchars($hasil['merk']); ?></h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Detail Kendaraan</h5>
                            <table class="table table-sm table-hover">
                                <tr>
                                    <td width="120">Merk</td>
                                    <td><strong><?= htmlspecialchars($hasil['merk']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <?php if($hasil['status'] == 'Tersedia'): ?>
                                            <span class="bg-success badge badge-success"><i class="fa fa-check"></i> Tersedia</span>
                                        <?php else: ?>
                                            <span class="bg-danger badge badge-danger"><i class="fa fa-times"></i> Tidak Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Harga Sewa</td>
                                    <td><strong>Rp. <?= number_format($hasil['harga']); ?></strong> / hari</td>
                                </tr>
                                <tr>
                                    <td>Bonus</td>
                                    <td><span class="badge badge-info"><i class="fa fa-check"></i> Gratis E-toll 50k</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Deskripsi</h5>
                            <p class="text-justify">
                                <?= nl2br(htmlspecialchars($hasil['deskripsi'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <?php if($hasil['status'] == 'Tersedia'): ?>
                                <a href="booking.php?id=<?= $hasil['id_mobil']; ?>" class="btn btn-success">
                                    <i class="fa fa-calendar-check-o"></i> Booking Sekarang
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fa fa-calendar-times-o"></i> Tidak Tersedia
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <!-- Booking Summary Card -->


            <!-- Related Cars Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Mobil Lainnya</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach($related_cars as $car): ?>
                            <a href="detail.php?id=<?= $car['id_mobil']; ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="media">
                                        <img src="assets/image/<?= htmlspecialchars($car['gambar']); ?>" class="mr-3"
                                        style="width: 70px; height: 50px; object-fit: cover;"
                                        alt="<?= htmlspecialchars($car['merk']); ?>">
                                        <div class="media-body">
                                            <h6 class="mt-0"><?= htmlspecialchars($car['merk']); ?></h6>
                                            <p class="mb-1 text-primary">Rp. <?= number_format($car['harga']); ?>/hari</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>

                        <?php if(count($related_cars) == 0): ?>
                            <div class="list-group-item">
                                <p class="mb-0 text-center text-muted">Tidak ada mobil sejenis saat ini</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="index.php" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-th"></i> Lihat Semua Mobil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script for calculating rental price -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglPinjam = document.getElementById('tgl_pinjam');
        const tglKembali = document.getElementById('tgl_kembali');
        const totalHarga = document.getElementById('total_harga');
        const hargaPerHari = <?= $hasil['harga']; ?>;

        function calculateTotalPrice() {
            if (tglPinjam.value && tglKembali.value) {
                const startDate = new Date(tglPinjam.value);
                const endDate = new Date(tglKembali.value);

            // Calculate days difference
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    const total = diffDays * hargaPerHari;
                    totalHarga.value = total.toLocaleString('id-ID');
                } else {
                    totalHarga.value = "0";
                }
            }
        }

    // Add event listeners
        tglPinjam.addEventListener('change', function() {
        // Update min date for return date
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);

        // Format the date as YYYY-MM-DD
            const formattedDate = nextDay.toISOString().split('T')[0];
            tglKembali.min = formattedDate;

        // If return date is before new min date, update it
            if (tglKembali.value && new Date(tglKembali.value) < nextDay) {
                tglKembali.value = formattedDate;
            }

            calculateTotalPrice();
        });

        tglKembali.addEventListener('change', calculateTotalPrice);
    });

// Image gallery lightbox (can be expanded with a lightbox library)
    document.addEventListener('DOMContentLoaded', function() {
        const carouselImages = document.querySelectorAll('#carDetailSlider .carousel-item img');

        carouselImages.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
            // Here you could implement a lightbox to show the full-size image
            // For example, using a library like fancybox or a custom implementation
                console.log('Image clicked:', this.src);
            });
        });
    });
</script>

<style>
/* Add some custom styles */
.badge {
    padding: 0.5em 0.75em;
}

/* Make reviews look better */
.media img {
    border: 1px solid #eee;
}

/* Improve hover effects */
.list-group-item-action:hover {
    background-color: #f8f9fa;
}

/* Style for sticky booking form */
.sticky-top {
    z-index: 100;
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative;
        top: 0 !important;
    }
}
</style>

<?php include 'footer.php'; ?>