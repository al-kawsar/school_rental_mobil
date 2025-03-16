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
                $kode_booking = $_GET['id'];
                $hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();
                $id = $hasil['id_mobil'];
                $isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();
                ?>

<!-- Modern UI CSS -->
<style>
    :root {
        --primary-color: #3563E9;
        --secondary-color: #54A6FF;
        --accent-color: #5CAFFC;
        --light-bg: #F6F7F9;
        --dark-text: #1A202C;
        --medium-text: #596780;
        --light-text: #90A3BF;
        --success: #39C69B;
        --warning: #FF9619;
        --danger: #FF4423;
    }

    body {
        background-color: var(--light-bg);
    }

    .payment-container {
        padding: 40px 0;
    }

    .payment-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px;
        position: relative;
    }

    .payment-info {
        background-color: white;
        padding: 25px;
        border-radius: 16px;
    }

    .payment-method {
        padding: 25px;
        background-color: white;
        position: relative;
        overflow: hidden;
    }

    .payment-method:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
    }

    .payment-form input.form-control {
        height: 50px;
        border-radius: 10px;
        border: 1px solid #E0E4EC;
        padding-left: 15px;
        transition: all 0.3s;
        margin-bottom: 12px;
    }

    .payment-form input.form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(53, 99, 233, 0.1);
    }

    .form-control::placeholder {
        color: var(--light-text);
    }

    .payment-form label {
        color: var(--medium-text);
        font-weight: 500;
        margin-bottom: 8px;
    }

    .booking-details {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #E0E4EC;
        padding: 12px 0;
    }

    .booking-details:last-child {
        border-bottom: none;
    }

    .booking-label {
        color: var(--medium-text);
        font-weight: 500;
    }

    .booking-value {
        color: var(--dark-text);
        font-weight: 600;
    }

    .total-price {
        color: var(--primary-color);
        font-size: 20px;
        font-weight: 700;
    }

    .bank-info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .bank-logo {
        width: 80px;
        height: 80px;
        background-color: var(--light-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .bank-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 5px;
    }

    .bank-account {
        font-size: 16px;
        color: var(--medium-text);
        margin-bottom: 5px;
    }

    .account-name {
        font-size: 14px;
        color: var(--light-text);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(53, 99, 233, 0.3);
        transition: all 0.3s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(53, 99, 233, 0.4);
    }

    .card-icon {
        margin-right: 8px;
    }

    .page-title {
        color: var(--dark-text);
        font-weight: 700;
        margin-bottom: 25px;
    }

    .car-info {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .car-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(53, 99, 233, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .car-icon i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .car-name {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 16px;
        margin: 0;
    }
</style>

<div class="container payment-container">
    <h3 class="page-title">Konfirmasi Pembayaran</h3>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="payment-card">
                <div class="payment-method">
                    <div class="bank-info">
                        <div class="bank-logo">
                            <i class="fa fa-university fa-2x" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="bank-name">Bank BRI</h5>
                        <div class="bank-account">2132131246</div>
                        <div class="account-name">A/N Andi Muh Raihan Alkawsar</div>
                    </div>

                    <hr>

                    <div class="car-info mt-4">
                        <div class="car-icon">
                            <i class="fa fa-car"></i>
                        </div>
                        <h6 class="car-name"><?php echo $isi['merk']; ?></h6>
                    </div>

                    <div class="booking-details">
                        <span class="booking-label">Kode Booking</span>
                        <span class="booking-value"><?php echo $hasil['kode_booking']; ?></span>
                    </div>

                    <div class="booking-details">
                        <span class="booking-label">Total Pembayaran</span>
                        <span class="booking-value total-price">Rp. <?php echo number_format($hasil['total_harga']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="payment-card">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fa fa-credit-card card-icon"></i>Detail Pembayaran</h5>
                </div>

                <div class="payment-info">
                    <form method="post" action="koneksi/proses.php?id=konfirmasi" class="payment-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_rekening">Nomor Rekening</label>
                                <input type="text" id="no_rekening" name="no_rekening" required class="form-control" placeholder="Masukkan nomor rekening">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama">Atas Nama</label>
                                <input type="text" id="nama" name="nama" required class="form-control" placeholder="Nama pemilik rekening">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nominal">Nominal Transfer</label>
                                <input type="text" id="nominal" name="nominal" required class="form-control" placeholder="Jumlah yang ditransfer">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tgl">Tanggal Transfer</label>
                                <input type="date" id="tgl" name="tgl" required class="form-control">
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle mr-2"></i>
                                <div>
                                    <strong>Penting!</strong> Pastikan data yang Anda masukkan sudah benar sebelum mengirim konfirmasi pembayaran.
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id_booking" value="<?php echo $hasil['id_booking']; ?>">

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="fa fa-paper-plane mr-2"></i>Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>