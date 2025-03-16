<?php
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0 font-weight-bold"><i class="fas fa-address-card mr-2"></i> Kontak Kami</h4>
        </div>
        <div class="card-body p-4">
          <div class="row mb-4">
            <div class="col-md-6 mb-4 mb-md-0">
              <div class="h-100 p-4 bg-light rounded">
                <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-building mr-2"></i>Informasi Perusahaan</h5>
                <ul class="list-unstyled">
                  <li class="mb-3">
                    <strong><i class="fas fa-car mr-2"></i>Nama Rental:</strong>
                    <p class="text-muted mb-0"><?= $info_web->nama_rental; ?></p>
                  </li>
                  <li class="mb-3">
                    <strong><i class="fas fa-map-marker-alt mr-2"></i>Alamat:</strong>
                    <p class="text-muted mb-0"><?= $info_web->alamat; ?></p>
                  </li>
                  <li>
                    <strong><i class="fas fa-credit-card mr-2"></i>No Rekening:</strong>
                    <p class="text-muted mb-0"><?= $info_web->no_rek; ?></p>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="h-100 p-4 bg-light rounded">
                <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-envelope mr-2"></i>Hubungi Kami</h5>
                <ul class="list-unstyled">
                  <li class="mb-3">
                    <strong><i class="fas fa-phone mr-2"></i>Telepon:</strong>
                    <p class="mb-0">
                      <a href="tel:<?= $info_web->telp; ?>" class="text-primary"><?= $info_web->telp; ?></a>
                    </p>
                  </li>
                  <li>
                    <strong><i class="fas fa-envelope mr-2"></i>Email:</strong>
                    <p class="mb-0">
                      <a href="mailto:<?= $info_web->email; ?>" class="text-primary"><?= $info_web->email; ?></a>
                    </p>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="bg-light p-4 rounded">
            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-comment mr-2"></i>Kirim Pesan</h5>
            <form>
              <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" placeholder="Masukkan nama lengkap">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Masukkan email">
              </div>
              <div class="form-group">
                <label for="message">Pesan</label>
                <textarea class="form-control" id="message" rows="3" placeholder="Tulis pesan anda..."></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
          </div>
        </div>
        <div class="card-footer bg-white text-center">
          <small class="text-muted">Jam Operasional: Senin - Jumat, 08:00 - 17:00 WIB</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Optional: Add map section -->
<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-0">
          <div class="ratio ratio-16x9" style="height: 300px;">
            <!-- Replace with your own map or remove if not needed -->
            <div class="bg-light text-center d-flex align-items-center justify-content-center">
              <p class="mb-0 text-muted">Peta Lokasi (Ganti dengan Google Maps)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Make sure to include Font Awesome for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>