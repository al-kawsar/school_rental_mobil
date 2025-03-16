<!doctype html>
<html lang="en">

<head>
  <title>Rental Mobil</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="https://www.iconarchive.com/download/i87053/graphicloads/colorful-long-shadow/Car.ico"
    type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --primary-color: #0d6efd;
      --dark-color: #212529;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .shadow-sm {
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    .navbar {
      padding: 0.8rem 0;
    }

    .navbar-dark {
      background-color: var(--dark-color) !important;
    }

    .btn-primary {
      background-color: var(--primary-color);
    }

    .header-container {
      padding: 1.5rem 0;
    }

    .search-form .btn {
      padding: 0.375rem 0.75rem;
    }

    .nav-link.active {
      font-weight: 600;
      position: relative;
    }

    .nav-link.active:after {
      content: '';
      position: absolute;
      left: 0.5rem;
      right: 0.5rem;
      bottom: -0.4rem;
      height: 3px;
      background-color: var(--primary-color);
      border-radius: 2px;
    }
  </style>
</head>

<body>
  <!-- <div class="header-container">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-7">
        </div>
        <div class="col-md-5">
          <form class="search-form d-flex" method="post" action="blog.php">
            <div class="input-group">
              <input class="form-control" type="search" name="cari" placeholder="Cari Nama Mobil" aria-label="Search">
              <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> -->

  <?php
  // Mendapatkan nama file halaman saat ini
  $current_page = basename($_SERVER['PHP_SELF']);
  ?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
        aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <h3 class="fw-bold text-white me-5"><?= htmlspecialchars($info_web->nama_rental); ?></h3>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Daftar Mobil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : ''; ?>" href="kontak.php">Kontak
              Kami</a>
          </li>
          <?php if (isset($_SESSION['USER'])) { ?>
            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'history.php') ? 'active' : ''; ?>" href="history.php">History</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'profil.php') ? 'active' : ''; ?>" href="profil.php">Profil</a>
            </li>
          <?php } ?>
        </ul>
        <?php if (isset($_SESSION['USER'])) { ?>
          <div class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-user me-1"></i> Hallo, <?= htmlspecialchars($_SESSION['USER']['nama_pengguna']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item <?= ($current_page == 'profil.php') ? 'active' : ''; ?>" href="profil.php"><i
                      class="fa-solid fa-user-gear me-2"></i>Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#" id="logout-btn"><i
                      class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
              </ul>
            </li>
          </div>
        <?php } else { ?>
          <div class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'login.php') ? 'active' : ''; ?>" href="login.php"><i
                  class="fa-solid fa-right-to-bracket me-1"></i> Login</a>
            </li>
          </div>
        <?php } ?>
      </div>
    </div>
  </nav>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <?php if (isset($_SESSION['USER'])) { ?>
    <script>
      document.getElementById('logout-btn').addEventListener('click', function (event) {
        event.preventDefault(); // Mencegah redirect langsung

        Swal.fire({
          title: "Konfirmasi Logout",
          text: "Apakah Anda yakin ingin logout?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya, Logout!"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "<?= $url; ?>admin/logout.php"; // Redirect jika dikonfirmasi
          }
        });
      });
    </script>
  <?php } ?>