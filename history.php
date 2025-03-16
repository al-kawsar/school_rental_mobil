<?php
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';
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
$id_user = $_SESSION['USER']['id_user'];

$hasil = $koneksi->prepare("SELECT mobil.merk, booking.* 
                            FROM booking 
                            JOIN mobil ON booking.id_mobil = mobil.id_mobil 
                            WHERE booking.id_user = ? 
                            ORDER BY id_booking DESC");
$hasil->execute([$id_user]);
$data = $hasil->fetchAll();
?>

<!-- Modern UI CSS -->
<style>
    :root {
        --primary-color: #3563E9;
        --secondary-color: #54A6FF;
        --light-bg: #F6F7F9;
        --dark-text: #1A202C;
        --medium-text: #596780;
        --light-text: #90A3BF;
        --success: #39C69B;
        --warning: #FF9619;
        --danger: #FF4423;
        --pending: #FFC107;
        --confirmed: #198754;
    }

    body {
        background-color: var(--light-bg);
    }

    .transaction-container {
        padding: 40px 0;
    }

    .page-title {
        color: var(--dark-text);
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .page-title i {
        background-color: rgba(53, 99, 233, 0.1);
        color: var(--primary-color);
        padding: 10px;
        border-radius: 10px;
        margin-right: 15px;
    }

    .transaction-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 0 !important;
        padding: 20px;
        font-weight: 600;
        font-size: 18px;
        border: none;
    }

    .table-container {
        padding: 20px;
        background-color: white;
        border-radius: 0 0 16px 16px;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .modern-table th {
        background-color: #f8f9fa;
        color: var(--medium-text);
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
    }

    .modern-table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        color: var(--dark-text);
    }

    .modern-table tbody tr:hover {
        background-color: rgba(53, 99, 233, 0.03);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .btn-detail {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 8px;
        color: white;
        padding: 8px 15px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(53, 99, 233, 0.2);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(53, 99, 233, 0.3);
    }

    .btn-detail i {
        margin-right: 5px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        min-width: 100px;
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.15);
        color: var(--pending);
    }

    .status-confirmed {
        background-color: rgba(25, 135, 84, 0.15);
        color: var(--confirmed);
    }

    .status-cancelled {
        background-color: rgba(255, 68, 35, 0.15);
        color: var(--danger);
    }

    .booking-code {
        font-weight: 600;
        color: var(--primary-color);
    }

    .price {
        font-weight: 600;
        color: var(--dark-text);
    }

    .search-filter {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .search-input {
        position: relative;
        width: 300px;
    }

    .search-input input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 10px;
        border: 1px solid #E0E4EC;
        background-color: white;
    }

    .search-input i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--light-text);
    }

    .filter-buttons button {
        background-color: white;
        border: 1px solid #E0E4EC;
        color: var(--medium-text);
        padding: 8px 15px;
        border-radius: 8px;
        margin-left: 10px;
        transition: all 0.3s;
    }

    .filter-buttons button:hover,
    .filter-buttons button.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .pagination-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-info {
        color: var(--medium-text);
        font-size: 14px;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
    }

    .pagination-button {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        border: 1px solid #E0E4EC;
        margin: 0 5px;
        transition: all 0.3s;
        cursor: pointer;
        color: var(--medium-text);
    }

    .pagination-button:hover,
    .pagination-button.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .car-name {
        display: flex;
        align-items: center;
    }

    .car-icon {
        width: 35px;
        height: 35px;
        background-color: rgba(53, 99, 233, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    .car-icon i {
        color: var(--primary-color);
        font-size: 16px;
    }

    .responsive-table {
        overflow-x: auto;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .search-filter {
            flex-direction: column;
        }

        .search-input {
            width: 100%;
            margin-bottom: 15px;
        }

        .filter-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .filter-buttons button {
            flex: 1;
            margin: 0 5px;
        }
    }
</style>

<div class="container transaction-container">
    <h3 class="page-title">
        <i class="fa fa-exchange"></i>
        Daftar Transaksi
    </h3>

    <div class="search-filter">
        <div class="search-input">
            <i class="fa fa-search"></i>
            <input type="text" id="searchTransaction" placeholder="Cari transaksi..." class="form-control">
        </div>
        <div class="filter-buttons">
            <button class="active">Semua</button>
            <button>Belum Bayar</button>
            <button>Sudah Bayar</button>
        </div>
    </div>

    <div class="transaction-card">
        <div class="card-header-custom">
            <i class="fa fa-list-alt mr-2"></i>
            Riwayat Transaksi
        </div>
        <div class="table-container">
            <div class="responsive-table">
                <table class="modern-table" id="transactionTable">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="10%">Kode Booking</th>
                            <th width="15%">Mobil</th>
                            <th width="10%">Nama</th>
                            <th width="12%">Tanggal Sewa</th>
                            <th width="8%">Durasi</th>
                            <th width="12%">Total</th>
                            <th width="13%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($data as $item) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><span class="booking-code"><?= $item['kode_booking']; ?></span></td>
                                <td>
                                    <div class="car-name">
                                        <div class="car-icon"><i class="fa fa-car"></i></div>
                                        <?= $item['merk']; ?>
                                    </div>
                                </td>
                                <td><?= $item['nama']; ?></td>
                                <td><?= $item['tanggal']; ?></td>
                                <td><?= $item['lama_sewa']; ?> hari</td>
                                <td><span class="price">Rp. <?= number_format($item['total_harga']); ?></span></td>
                                <td>
                                    <?php if ($item['konfirmasi_pembayaran'] == 'Belum Bayar') { ?>
                                        <span class="status-badge status-pending">Belum Bayar</span>
                                    <?php } else if ($item['konfirmasi_pembayaran'] == 'Sudah Bayar') { ?>
                                            <span class="status-badge status-confirmed">Sudah Bayar</span>
                                    <?php } else { ?>
                                            <span
                                                class="status-badge status-cancelled"><?= $item['konfirmasi_pembayaran']; ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="btn btn-detail" href="bayar.php?id=<?= $item['kode_booking']; ?>">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php $no++;
                        } ?>
                    </tbody>

                </table>
            </div>

            <div class="pagination-custom">
                <div class="pagination-info">
                    Menampilkan 1-<?php echo count($data); ?> dari <?php echo count($data); ?> transaksi
                </div>
                <div class="pagination-controls">
                    <div class="pagination-button"><i class="fa fa-angle-left"></i></div>
                    <div class="pagination-button active">1</div>
                    <div class="pagination-button"><i class="fa fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add simple search functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchTransaction');
        const table = document.getElementById('transactionTable');
        const rows = table.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function () {
            const query = searchInput.value.toLowerCase();

            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().indexOf(query) > -1) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        });

        // Filter buttons (for demonstration - would need backend implementation for full functionality)
        const filterButtons = document.querySelectorAll('.filter-buttons button');

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                // This is just for UI demonstration
                // In a real implementation, you would filter based on status
            });
        });
    });

    const filterButtons = document.querySelectorAll('.filter-buttons button');
    const transactionRows = document.querySelectorAll('#transactionTable tbody tr');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(button => button.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.textContent.toUpperCase();
            transactionRows.forEach(row => {
                const status = row.querySelector('.status-badge').textContent.toUpperCase();
                if (filterValue === 'SEMUA') {
                    row.style.display = '';
                } else if (status.includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>