<?php
require '../koneksi/koneksi.php';
$title_web = 'Settings';
include 'header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}

if(!empty($_POST['nama_rental'])) {
    $data[] = htmlspecialchars($_POST["nama_rental"]);
    $data[] = htmlspecialchars($_POST["telp"]);
    $data[] = htmlspecialchars($_POST["alamat"]);
    $data[] = htmlspecialchars($_POST["email"]);
    $data[] = htmlspecialchars($_POST["no_rek"]);
    $data[] = 1;
    $sql = "UPDATE infoweb SET nama_rental = ?, telp = ?, alamat = ?, email = ?, no_rek = ? WHERE id = ?";
    $row = $koneksi->prepare($sql);
    $row->execute($data);
    echo '<script>
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "Update Data Info Website Berhasil!",
        showConfirmButton: false,
        timer: 1500
        }).then(function() {
            window.reload();
            });
            </script>';
        }
        $sql = "SELECT * FROM infoweb WHERE id = 1";
        $row = $koneksi->prepare($sql);
        $row->execute();
        $edit = $row->fetch(PDO::FETCH_OBJ);
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <div class="container mt-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Info Website</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="formWebsite">
                                <div class="form-group mb-3">
                                    <label for="nama_rental" class="form-label fw-bold">Nama Rental</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit->nama_rental); ?>" name="nama_rental" id="nama_rental" placeholder="Masukkan nama rental" required/>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-bold">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" value="<?= htmlspecialchars($edit->email); ?>" name="email" id="email" placeholder="Masukkan email" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telp" class="form-label fw-bold">Telepon</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($edit->telp); ?>" name="telp" id="telp" placeholder="Masukkan nomor telepon" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="alamat" class="form-label fw-bold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($edit->alamat); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="no_rek" class="form-label fw-bold">Nomor Rekening</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <textarea class="form-control" name="no_rek" id="no_rek" rows="2" placeholder="Masukkan informasi rekening"><?= htmlspecialchars($edit->no_rek); ?></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .card {
                transition: transform 0.3s, box-shadow 0.3s;
                border-radius: 10px;
                overflow: hidden;
            }
            .card-header {
                border-bottom: none;
                padding: 15px 20px;
            }
            .form-label {
                margin-bottom: 0.3rem;
            }
            .input-group-text {
                background-color: #f8f9fa;
                width: 40px;
                justify-content: center;
            }
            .avatar-circle {
                width: 100px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background-color: #f8f9fa;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const formWebsite = document.getElementById('formWebsite');
                formWebsite.addEventListener('submit', function(event) {
                    if (!formWebsite.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harap isi semua field yang diperlukan!',
                        });
                    }
                    formWebsite.classList.add('was-validated');
                });


            });
        </script>

        <?php include 'footer.php'; ?>