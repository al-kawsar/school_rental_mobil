<?php
require_once '../../koneksi/koneksi.php';
$title_web = 'User';
include '../header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}

// Pagination setup
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM users WHERE level = 'Pengguna' ORDER BY id_user DESC LIMIT $limit OFFSET $offset";
$row = $koneksi->prepare($sql);
$row->execute();
$hasil = $row->fetchAll(PDO::FETCH_OBJ);

$totalQuery = $koneksi->prepare("SELECT COUNT(*) as total FROM users WHERE level = 'Pengguna'");
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
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari pengguna...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-primary rounded-pill" id="totalUsers">
                        Total: <?= $totalData ?> pengguna
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-light">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Nama Pengguna</th>
                            <th>Username</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($hasil) {
                            $no = $offset + 1;
                            foreach($hasil as $r) {
                                ?>
                                <tr class="user-row">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $r->nama_pengguna; ?></td>
                                    <td><?= $r->username; ?></td>
                                    <td class="text-center">
                                        <a href="<?= $url; ?>admin/booking/booking.php?id=<?= $r->id_user; ?>" class="btn btn-primary btn-sm rounded-pill">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 text-light"></i>
                                    <p>Belum ada data pengguna</p>
                                </td>
                            </tr>
                        <?php } ?>
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
    // Set user count
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');

            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const username = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (name.includes(searchValue) || username.includes(searchValue)) {
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