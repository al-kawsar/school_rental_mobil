<?php
require '../../koneksi/koneksi.php';
session_start();

if(empty($_SESSION['USER'])) {
    header("Location: ../login.php");
    exit;
}

$title_web = 'Daftar Mobil';
include '../header.php';

// Pagination setup
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM mobil ORDER BY updated_at DESC LIMIT :limit OFFSET :offset";
$row = $koneksi->prepare($sql);
$row->bindValue(':limit', $limit, PDO::PARAM_INT);
$row->bindValue(':offset', $offset, PDO::PARAM_INT);
$row->execute();
$hasil = $row->fetchAll(PDO::FETCH_OBJ);

$totalQuery = $koneksi->prepare("SELECT COUNT(*) as total FROM mobil");
$totalQuery->execute();
$totalData = $totalQuery->fetch(PDO::FETCH_OBJ)->total;
$totalPages = ceil($totalData / $limit);
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
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari mobil...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a class="btn btn-primary" href="tambah.php" role="button">Tambah Mobil</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-light">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th width="20%">Gambar</th>
                            <th width="15%">Merk Mobil</th>
                            <th width="10%">No Plat</th>
                            <th width="15%">Harga</th>
                            <th width="10%">Status</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($hasil): ?>
                            <?php $no = $offset + 1; ?>
                            <?php foreach($hasil as $isi): ?>
                                <tr class="car-row">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><img src="../../assets/image/<?= htmlspecialchars($isi->gambar); ?>" class="img-fluid" style="width:100%;height: 130px; object-fit: cover; object-position: center;"></td>
                                    <td><?= htmlspecialchars($isi->merk); ?></td>
                                    <td><?= htmlspecialchars($isi->no_plat); ?></td>
                                    <td><?= 'Rp ' . number_format($isi->harga, 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?php echo ($isi->status == 'Tersedia') ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo htmlspecialchars($isi->status); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($isi->deskripsi); ?></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 h-100">
                                            <a href="edit.php?id=<?= $isi->id_mobil; ?>" class="btn btn-primary btn-sm rounded-pill">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a onclick="confirmDelete('<?= $isi->id_mobil; ?>', '<?= $isi->gambar; ?>')" class="btn btn-danger btn-sm rounded-pill">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-car fa-3x mb-3 text-light"></i>
                                    <p>Belum ada data mobil</p>
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
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('.car-row').forEach(row => {
                const merk = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const noPlat = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                row.style.display = (merk.includes(searchValue) || noPlat.includes(searchValue)) ? '' : 'none';
            });
        });

        document.getElementById('rowsPerPage').addEventListener('change', function() {
            window.location.href = "?page=1&limit=" + this.value;
        });
    });

    function confirmDelete(id, gambar) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Mobil ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "proses.php?aksi=hapus&id=" + id + "&gambar=" + gambar;
            }
        });
    }
</script>

<?php include '../footer.php'; ?>
