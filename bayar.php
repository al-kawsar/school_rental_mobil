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
                    window.location = "index.php";
                    } else {
                        window.location = "login.php";
                    }
                    });
                    });
                    </script>';
                    exit;
                }

                $kode_booking = $_GET['id'];
                $hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();
                $id = $hasil['id_mobil'];
                $isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();
                $unik = random_int(100,999);
                ?>

<!-- CSS Enhancements -->
<style>
  .booking-card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.booking-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    padding: 15px 20px;
}

.booking-body {
    padding: 20px;
}

.detail-table tr td {
    padding: 12px 8px;
    border-bottom: 1px solid #f0f0f0;
}

.detail-table tr:last-child td {
    border-bottom: none;
}

.car-info {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.payment-info {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.status-badge {
    padding: 8px 12px;
    border-radius: 20px;
    color: white;
    font-weight: 500;
    display: inline-block;
}

.confirm-btn {
    padding: 10px 20px;
    border-radius: 5px;
    transition: all 0.3s;
}

.confirm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>

<div class="container py-5">
  <div class="row">
    <!-- Car and Payment Info Column -->
    <div class="col-lg-4 mb-4">
      <!-- Payment Information Card -->
      <div class="payment-info mb-4">
        <div class="card-body text-center py-4">
          <h5 class="card-title mb-3">Metode Pembayaran</h5>
          <div class="d-flex justify-content-center mb-3">
            <i class="fa fa-credit-card fa-2x text-primary"></i>
        </div>
        <hr class="my-3">
        <p class="mb-0 font-weight-bold"><?= $info_web->no_rek; ?></p>
    </div>
</div>

<!-- Car Information Card -->
<div class="car-info">
    <div class="card-header py-3" style="background:#f8f9fc">
      <h5 class="m-0 font-weight-bold text-primary"><?php echo $isi['merk']; ?></h5>
  </div>
  <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        <?php if($isi['status'] == 'Tersedia'){ ?>
          <li class="list-group-item bg-success text-white">
            <i class="fa fa-check-circle mr-2"></i> Tersedia
        </li>
    <?php } else { ?>
      <li class="list-group-item bg-danger text-white">
        <i class="fa fa-times-circle mr-2"></i> Tidak Tersedia
    </li>
<?php } ?>

<li class="list-group-item bg-info text-white">
  <i class="fa fa-gift mr-2"></i> Gratis E-toll 50k
</li>

<li class="list-group-item bg-dark text-white">
  <i class="fa fa-tag mr-2"></i> Rp. <?php echo number_format($isi['harga']); ?>/ hari
</li>
</ul>
</div>
</div>
</div>

<!-- Booking Details Column -->
<div class="col-lg-8">
  <div class="booking-card">
    <div class="booking-header">
      <h4 class="mb-0">Detail Pemesanan</h4>
  </div>
  <div class="booking-body">
      <div class="table-responsive">
        <table class="table detail-table table-borderless">
          <tr>
            <td width="30%"><strong>Kode Booking</strong></td>
            <td width="5%">:</td>
            <td><?php echo $hasil['kode_booking']; ?></td>
        </tr>
        <tr>
            <td><strong>KTP</strong></td>
            <td>:</td>
            <td><?php echo $hasil['ktp']; ?></td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td>:</td>
            <td><?php echo $hasil['nama']; ?></td>
        </tr>
        <tr>
            <td><strong>Telepon</strong></td>
            <td>:</td>
            <td><?php echo $hasil['no_tlp']; ?></td>
        </tr>
        <tr>
            <td><strong>Tanggal Sewa</strong></td>
            <td>:</td>
            <td><?php echo $hasil['tanggal']; ?></td>
        </tr>
        <tr>
            <td><strong>Lama Sewa</strong></td>
            <td>:</td>
            <td><?php echo $hasil['lama_sewa']; ?> hari</td>
        </tr>
        <tr>
            <td><strong>Total Harga</strong></td>
            <td>:</td>
            <td class="font-weight-bold">Rp. <?php echo number_format($hasil['total_harga']); ?></td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>:</td>
            <td>
              <?php if($hasil['konfirmasi_pembayaran'] == 'Belum Bayar'){ ?>
                <span class="status-badge bg-warning">Belum Bayar</span>
            <?php } else if($hasil['konfirmasi_pembayaran'] == 'Sudah Bayar'){ ?>
                <span class="status-badge bg-success">Sudah Bayar</span>
            <?php } else { ?>
                <span class="status-badge bg-secondary"><?php echo $hasil['konfirmasi_pembayaran']; ?></span>
            <?php } ?>
        </td>
    </tr>
</table>
</div>

<?php if($hasil['konfirmasi_pembayaran'] == 'Belum Bayar'){ ?>
    <div class="text-right mt-3">
      <a href="konfirmasi.php?id=<?php echo $kode_booking; ?>"
       class="btn btn-primary confirm-btn">
       <i class="fa fa-check-circle mr-2"></i>Konfirmasi Pembayaran
   </a>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>