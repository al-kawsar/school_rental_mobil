<?php
require_once '../../koneksi/koneksi.php';
$title_web = 'Edit Mobil';
include '../header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}
$id = $_GET['id'];

$sql = "SELECT * FROM mobil WHERE id_mobil = ?";
$row = $koneksi->prepare($sql);
$row->execute(array($id));

$hasil = $row->fetch();
?>

<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
                <i class="fas fa-car mr-2"></i> Edit Mobil - <?= htmlspecialchars($hasil['merk']); ?>
            </h4>
            <a class="btn btn-light" href="mobil.php">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=edit&id=<?= $id; ?>" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control" id="no_plat" value="<?= htmlspecialchars($hasil['no_plat']); ?>" name="no_plat" placeholder="Masukkan No Plat" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="merk"><strong>Merk Mobil</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-car-side"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="merk" value="<?= htmlspecialchars($hasil['merk']); ?>" name="merk" placeholder="Masukkan Merk Mobil" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="harga"><strong>Harga Sewa</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" id="harga" value="<?= htmlspecialchars($hasil['harga']); ?>" name="harga" placeholder="Masukkan Harga Sewa" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">/hari</span>
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
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Mobil"><?= htmlspecialchars($hasil['deskripsi']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status"><strong>Status Ketersediaan</strong></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="" disabled>Pilih Status</option>
                                        <option value="Tersedia" <?php if($hasil['status'] == 'Tersedia'){ echo 'selected';} ?>>
                                            Tersedia
                                        </option>
                                        <option value="Tidak Tersedia" <?php if($hasil['status'] == 'Tidak Tersedia'){ echo 'selected';} ?>>
                                            Tidak Tersedia
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Foto Mobil</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gambar"><strong>Upload Gambar Baru</strong></label>
                                    <input type="file" class="form-control-file" id="gambar" name="gambar" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, atau GIF. Maks: 2MB</small>
                                    <input type="hidden" value="<?= htmlspecialchars($hasil['gambar']); ?>" name="gambar_cek">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label><strong>Gambar Saat Ini</strong></label>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <?php if(!empty($hasil['gambar'])): ?>
                                            <img src="../../assets/image/<?php echo htmlspecialchars($hasil['gambar']); ?>" class="img-fluid rounded" style="max-height: 200px;">
                                        <?php else: ?>
                                            <div class="alert alert-info">Belum ada gambar tersedia</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>