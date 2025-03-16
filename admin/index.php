<?php
require_once '../koneksi/koneksi.php';
$title_web = 'Dashboard';
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


try {
    $query = "
    SELECT
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM mobil) AS total_mobil,
        (SELECT COUNT(*) FROM booking) AS total_booking,
        (SELECT COUNT(*) FROM mobil WHERE status = 'Tersedia') AS mobil_tersedia,
        (SELECT COUNT(*) FROM mobil WHERE status = 'Tidak Tersedia') AS mobil_tidak_tersedia,
        (SELECT SUM(total_harga) FROM booking) AS total_pendapatan
    ";

    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_OBJ);

    // Get recent bookings
    $queryBooking = "
    SELECT b.kode_booking, b.tanggal, b.total_harga, b.konfirmasi_pembayaran, 
           u.nama_pengguna, m.merk, m.no_plat
    FROM booking b
    JOIN users u ON b.id_user = u.id_user
    JOIN mobil m ON b.id_mobil = m.id_mobil
    ORDER BY b.id_booking DESC
    LIMIT 5
    ";

    $stmtBooking = $koneksi->prepare($queryBooking);
    $stmtBooking->execute();
    $recentBookings = $stmtBooking->fetchAll(PDO::FETCH_OBJ);

    // Get popular cars
    $queryPopular = "
    SELECT m.id_mobil, m.merk, m.no_plat, m.harga, m.status, m.gambar,
           COUNT(b.id_booking) as total_bookings
    FROM mobil m
    LEFT JOIN booking b ON m.id_mobil = b.id_mobil
    GROUP BY m.id_mobil
    ORDER BY total_bookings DESC
    LIMIT 4
    ";

    $stmtPopular = $koneksi->prepare($queryPopular);
    $stmtPopular->execute();
    $popularCars = $stmtPopular->fetchAll(PDO::FETCH_OBJ);

    // Get monthly booking data for chart
    $queryChartData = "
    SELECT DATE_FORMAT(tanggal, '%Y-%m') as month, COUNT(*) as bookings
    FROM booking
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
    ORDER BY month ASC
    ";

    $stmtChartData = $koneksi->prepare($queryChartData);
    $stmtChartData->execute();
    $bookingChartData = $stmtChartData->fetchAll(PDO::FETCH_OBJ);

    // Format chart data for JavaScript
    $chartLabels = [];
    $chartValues = [];
    foreach ($bookingChartData as $data) {
        $chartLabels[] = $data->month;
        $chartValues[] = $data->bookings;
    }

} catch (PDOException $e) {
    die("Terjadi kesalahan: " . htmlspecialchars($e->getMessage()));
}

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<div class="container-fluid py-4">
    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats->total_users; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Mobil</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats->total_mobil; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Booking</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats->total_booking; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                <?php echo number_format($stats->total_pendapatan, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Bookings Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Overview</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Car Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Car Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="carStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Tersedia
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Tidak Tersedia
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Cars and Recent Bookings -->
    <div class="row">
        <!-- Popular Cars -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Popular Cars</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($popularCars as $car): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $car->merk; ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $car->no_plat; ?></h6>
                                        <p class="card-text">
                                            <span
                                                class="badge <?php echo ($car->status == 'Tersedia') ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $car->status; ?>
                                            </span>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-money-bill-wave"></i> Rp
                                                <?php echo number_format($car->harga, 0, ',', '.'); ?>/day
                                            </small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-check"></i> <?php echo $car->total_bookings; ?>
                                                bookings
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking->kode_booking; ?></td>
                                        <td><?php echo $booking->nama_pengguna; ?></td>
                                        <td><?php echo $booking->merk; ?></td>
                                        <td><?php echo date("d M Y", strtotime($booking->tanggal)); ?></td>
                                        <td>
                                            <span
                                                class="badge <?php echo ($booking->konfirmasi_pembayaran == 'Pembayaran di terima') ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo ($booking->konfirmasi_pembayaran == 'Pembayaran di terima') ? 'Confirmed' : 'Pending'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Booking search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('.booking-row');

                rows.forEach(row => {
                    const merk = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const nama = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const kode = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                    if (merk.includes(searchValue) || nama.includes(searchValue) || kode.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Rows per page functionality
        const rowsPerPage = document.getElementById('rowsPerPage');
        if (rowsPerPage) {
            rowsPerPage.addEventListener('change', function () {
                window.location.href = "?page=1&limit=" + this.value;
            });
        }

        // Charts
        // Booking Chart
        var bookingChartCanvas = document.getElementById('bookingChart');
        if (bookingChartCanvas) {
            var bookingChart = new Chart(bookingChartCanvas, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($chartLabels ?? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]); ?>,
                    datasets: [{
                        label: "Bookings",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: <?php echo json_encode($chartValues ?? [5, 7, 8, 10, 12, 9]); ?>,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                beginAtZero: true
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10
                    }
                }
            });
        }

        // Car Status Chart
        var carStatusChartCanvas = document.getElementById('carStatusChart');
        if (carStatusChartCanvas) {
            var carStatusChart = new Chart(carStatusChartCanvas, {
                type: 'doughnut',
                data: {
                    labels: ["Tersedia", "Tidak Tersedia"],
                    datasets: [{
                        data: [
                            <?php echo isset($stats->mobil_tersedia) ? $stats->mobil_tersedia : 0; ?>,
                            <?php echo isset($stats->mobil_tidak_tersedia) ? $stats->mobil_tidak_tersedia : 0; ?>
                        ],
                        backgroundColor: ['#2ecc71', '#e74c3c'],
                        hoverBackgroundColor: ['#27ae60', '#c0392b'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
    });
</script>
<?php include 'footer.php'; ?>