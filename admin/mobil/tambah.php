<?php
require_once '../../koneksi/koneksi.php';
$title_web = 'Tambah Mobil';
include '../header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}
  // Preload common validation functions
  $js_validation = true; // Flag for header to include validation JS
  ?>

  <div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Mobil Baru
            </h4>
            <a class="btn btn-light" href="mobil.php">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=tambah" enctype="multipart/form-data" id="formTambahMobil" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Informasi Dasar</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="no_plat"><strong>No Plat</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="no_plat" name="no_plat"
                                        placeholder="Contoh: B 1234 CD" required
                                        pattern="[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}">
                                        <div class="invalid-feedback">
                                            Masukkan nomor plat dengan format yang benar
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="merk"><strong>Merk Mobil</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-car-side"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="merk" name="merk"
                                        placeholder="Contoh: Toyota Avanza" required>
                                        <div class="invalid-feedback">
                                            Merk mobil tidak boleh kosong
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="harga"><strong>Harga Sewa</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" id="harga" name="harga"
                                        placeholder="Contoh: 300000" min="0" step="1000" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">/hari</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Masukkan harga yang valid
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Detail & Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="deskripsi"><strong>Deskripsi</strong></label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Contoh: Mobil 7 Seater, AC, Full Music" required></textarea>
                                    <div class="invalid-feedback">
                                        Deskripsi singkat diperlukan
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status"><strong>Status Ketersediaan</strong></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="" disabled selected>Pilih Status</option>
                                        <option value="Tersedia">Tersedia</option>
                                        <option value="Tidak Tersedia">Tidak Tersedia</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Pilih status ketersediaan mobil
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="gambar"><strong>Foto Mobil</strong></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="gambar" name="gambar"
                                        accept="image/jpeg,image/png,image/gif" required>
                                        <label class="custom-file-label" for="gambar">Pilih file gambar...</label>
                                        <div class="invalid-feedback">
                                            Silakan pilih foto mobil
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, atau GIF. Ukuran maksimal: 2MB
                                    </small>
                                    <div class="mt-3">
                                        <div id="imagePreview" class="text-center d-none">
                                            <img src="" id="previewImg" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="reset" class="btn btn-secondary mr-2">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="fas fa-save mr-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom JavaScript for form validation and image preview -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Show filename when selected
        document.getElementById('gambar').addEventListener('change', function(e) {
        // Update file name display
            let fileName = e.target.files[0]?.name || 'Pilih file gambar...';
            let nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;

        // Image preview
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').setAttribute('src', e.target.result);
                    document.getElementById('imagePreview').classList.remove('d-none');
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

    // Form validation
        const form = document.getElementById('formTambahMobil');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

    // Optimize form submission - disable button to prevent double submissions
        form.addEventListener('submit', function() {
            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Menyimpan...';
        });
    });
</script>

<?php include '../footer.php'; ?>