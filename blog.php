<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

// Persiapkan query dengan prepared statement untuk mencegah SQL injection
if(isset($_GET['cari']) && !empty($_GET['cari'])) {
    $cari = trim(strip_tags($_GET['cari']));
    $stmt = $koneksi->prepare("SELECT * FROM mobil WHERE merk LIKE :cari ORDER BY id_mobil DESC");
    $stmt->execute([':cari' => "%$cari%"]);
    $query = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $showingText = "Hasil Pencarian: " . htmlspecialchars($cari);
} else {
    // Gunakan limit dan paginasi untuk performa yang lebih baik
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 12; // Menampilkan 12 mobil per halaman
    $offset = ($page - 1) * $limit;

    // Hitung total data untuk paginasi
    $total = $koneksi->query("SELECT COUNT(*) FROM mobil")->fetchColumn();
    $total_pages = ceil($total / $limit);

    $stmt = $koneksi->prepare("SELECT * FROM mobil ORDER BY id_mobil DESC LIMIT :offset, :limit");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $query = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $showingText = "Semua Mobil";
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="mb-0 fw-bold"><?= $showingText ?></h2>
            <p class="text-muted mt-2">Temukan mobil yang sesuai dengan kebutuhan Anda</p>
        </div>
        <div class="col-lg-4">
            <form method="get" action="" class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" name="cari" placeholder="Cari berdasarkan merk..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if(empty($query)): ?>
        <div class="alert alert-info">
            <i class="fa-solid fa-info-circle me-2"></i> Maaf, tidak ada mobil yang sesuai dengan pencarian Anda.
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach($query as $isi): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm hover-card">
                    <?php if(file_exists("assets/image/".$isi['gambar']) && !empty($isi['gambar'])): ?>
                    <img
                    src="assets/image/<?= htmlspecialchars($isi['gambar']) ?>"
                    class="card-img-top"
                    alt="<?= htmlspecialchars($isi['merk']) ?>"
                    style="height: 200px; object-fit: cover;"
                    loading="lazy"
                    >
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <i class="fa-solid fa-car fa-3x text-muted"></i>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($isi['merk']) ?></h5>
                    <?php if(!empty($isi['deskripsi'])): ?>
                        <p class="card-text small text-muted"><?= htmlspecialchars(substr($isi['deskripsi'], 0, 70)) ?>...</p>
                    <?php endif; ?>
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Status
                        <?php if($isi['status'] == 'Tersedia'): ?>
                            <span class="badge bg-success rounded-pill">
                                <i class="fa-solid fa-check me-1"></i>Tersedia
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill">
                                <i class="fa-solid fa-xmark me-1"></i>Tidak Tersedia
                            </span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Harga Sewa
                        <span class="fw-bold text-primary">Rp <?= number_format($isi['harga'], 0, ',', '.') ?>/hari</span>
                    </li>
                </ul>

                <div class="card-body d-grid gap-2">
                    <?php if($isi['status'] == 'Tersedia'): ?>
                        <a href="booking.php?id=<?= $isi['id_mobil'] ?>" class="btn btn-primary">
                            <i class="fa-solid fa-calendar-check me-1"></i> Booking Sekarang
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fa-solid fa-calendar-xmark me-1"></i> Tidak Tersedia
                        </button>
                    <?php endif; ?>
                    <a href="detail.php?id=<?= $isi['id_mobil'] ?>" class="btn btn-outline-primary">
                        <i class="fa-solid fa-circle-info me-1"></i> Lihat Detail
                    </a>
                </div>

                <?php if(!empty($isi['promo'])): ?>
                    <div class="card-footer bg-warning text-dark text-center">
                        <small><i class="fa-solid fa-tag me-1"></i> <?= htmlspecialchars($isi['promo']) ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if(!isset($_GET['cari']) && $total_pages > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page-1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page+1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
</div>

<style>
    .hover-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    @media (max-width: 576px) {
        .row.g-4 {
            margin-left: -8px;
            margin-right: -8px;
        }
        .col-md-6.col-lg-4 {
            padding-left: 8px;
            padding-right: 8px;
        }
    }
</style>

<?php include 'footer.php'; ?>