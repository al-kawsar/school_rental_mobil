<?php

require '../../koneksi/koneksi.php';
$title_web = 'Daftar Booking';
include '../header.php';

if (empty($_SESSION['USER'])) {
    session_start();
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    if (!empty($_GET['id'])) {
      $id = strip_tags($_GET['id']);
      $sql = "SELECT mobil.merk, booking.* FROM booking
      JOIN mobil ON booking.id_mobil = mobil.id_mobil
      WHERE id_login = :id
      ORDER BY id_booking DESC
      LIMIT :limit OFFSET :offset";
      $row = $koneksi->prepare($sql);
      $row->bindValue(':id', $id, PDO::PARAM_INT);
  } else {
    $sql = "SELECT login.nama_pengguna as nama_pengguna ,mobil.merk, mobil.status, booking.* FROM booking
    JOIN mobil ON booking.id_mobil = mobil.id_mobil
    JOIN login ON booking.id_login = login.id_login
    ORDER BY id_booking DESC
    LIMIT :limit OFFSET :offset";
    $row = $koneksi->prepare($sql);
}

$row->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$row->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$row->execute();
$hasil = $row->fetchAll(PDO::FETCH_OBJ);
$totalQuery = $koneksi->prepare("SELECT COUNT(*) as total FROM booking");
$totalQuery->execute();
$totalData = $totalQuery->fetch(PDO::FETCH_OBJ)->total;
$totalPages = ceil($totalData / $limit);

} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}

?>

<div class="container pb-5">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari booking...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-light">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>KODE</th>
                            <th>Merk Mobil</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Booking</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($hasil): ?>
                            <?php $no = $offset + 1; ?>
                            <?php foreach ($hasil as $isi): ?>
                                <tr class="booking-row">
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $isi->kode_booking; ?></td>
                                    <td><?php echo $isi->merk; ?></td>
                                    <td><?php echo $isi->nama_pengguna; ?></td>
                                    <td><?php echo date("d F Y", strtotime($isi->tanggal)); ?></td>
                                    <td>
                                        <span class="badge <?php echo ($isi->status == 'Tersedia') ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo htmlspecialchars($isi->status); ?>
                                        </span>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-check fa-3x mb-3 text-light"></i>
                                    <p>Belum ada data booking</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <select class="form-select form-select-sm" id="rowsPerPage">
                        <option value="10" <?= $limit == 10 ? 'selected' : ''; ?>>10 baris</option>
                        <option value="25" <?= $limit == 25 ? 'selected' : ''; ?>>25 baris</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : ''; ?>>50 baris</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : ''; ?>>100 baris</option>
                    </select>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=1&limit=<?= $limit; ?>">&laquo; First</a>
                        </li>
                        <li class="page-item <?= $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $page - 1; ?>&limit=<?= $limit; ?>">Sebelumnya</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#"><?= $page; ?></a></li>
                        <li class="page-item <?= $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $page + 1; ?>&limit=<?= $limit; ?>">Berikutnya</a>
                        </li>
                        <li class="page-item <?= $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $totalPages; ?>&limit=<?= $limit; ?>">Last &raquo;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('.booking-row');

            rows.forEach(row => {
                const merk = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const loginId = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (merk.includes(searchValue) || loginId.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('rowsPerPage').addEventListener('change', function() {
            window.location.href = "?page=1&limit=" + this.value;
        });
    });
</script>

<?php include '../footer.php'; ?>
