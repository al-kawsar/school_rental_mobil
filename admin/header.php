<?php
session_start();

if (empty($_SESSION['USER']) || $_SESSION['USER']['level'] !== 'admin') {
    echo '
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Restricted Access",
            text: "This area is restricted to admin users only.",
            icon: "warning",
            confirmButtonColor: "#3085d6"
            }).then((result) => {
                window.location.href = "../index.php";
                });
                });
                </script>';
    exit;

}
// Get admin details
$id_login = $_SESSION['USER']['id_user'];
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$id_login]);
$admin = $stmt->fetch();
// Security: regenerate session ID periodically to prevent session fixation
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration']) > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}


?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Car Rental Admin Panel">
    <title><?php echo htmlspecialchars($title_web); ?> | AL Rental</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo $url; ?>assets/img/favicon.ico">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="shortcut icon"
        href="https://www.iconarchive.com/download/i87053/graphicloads/colorful-long-shadow/Car.ico"
        type="image/x-icon">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/admin-style.css">
</head>

<body class="d-flex bg-light">
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid px-4">
                <button class="btn btn-outline-secondary border-0" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="breadcrumb-wrapper me-auto ms-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 py-2">
                            <li class="breadcrumb-item"><a href="<?php echo $url; ?>admin/">Admin</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php echo htmlspecialchars($title_web); ?></li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="position-relative me-3 text-muted" id="notificationDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-bell fs-5"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                2
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationDropdown"
                            style="min-width: 300px;">
                            <h6 class="dropdown-header">Notifikasi</h6>
                            <a class="dropdown-item py-2" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-car-side text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 fw-medium">Booking baru #234</p>
                                        <p class="text-muted small mb-0">10 menit yang lalu</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item py-2" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-user text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 fw-medium">Pelanggan baru terdaftar</p>
                                        <p class="text-muted small mb-0">30 menit yang lalu</p>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center small" href="#">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div class="content-wrapper container-fluid px-4">
            <div class="page-header mb-4">
                <h3 class="fw-bold"><?php echo htmlspecialchars($title_web); ?></h3>
                <p class="text-muted">Manage your <?php echo strtolower(htmlspecialchars($title_web)); ?> data</p>
            </div>

            <!-- Content goes here -->