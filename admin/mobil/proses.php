<?php
require_once '../../koneksi/koneksi.php';
$title_web = 'Tambah Mobil';
include '../header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function showAlert($message, $redirect = "tambah.php") {
    echo "<script>
    Swal.fire({
        icon: 'warning',
        title: 'Oops...',
        text: '$message'
        }).then(() => {
            window.location.href = '$redirect';
            });
            </script>";
            exit();
        }

        $allowedTypes = [
            'image/png'  => 'png',
            'image/jpeg' => 'jpg',
            'image/gif'  => 'gif',
            'image/jpg'  => 'jpeg',
            'image/webp' => 'webp'
        ];

        if (isset($_GET['aksi'])) {
            $aksi = $_GET['aksi'];

            if ($aksi === 'tambah' || $aksi === 'edit') {
                $isEdit = ($aksi === 'edit');
                $id = $_GET['id'] ?? null;
                $gambarLama = $_POST['gambar_cek'] ?? null;

                $data = [
                    $_POST['no_plat'],
                    $_POST['merk'],
                    $_POST['harga'],
                    $_POST['deskripsi'],
                    $_POST['status']
                ];

                if (!empty($_FILES['gambar']['name'])) {
                    $filepath = $_FILES['gambar']['tmp_name'];
                    $filetype = mime_content_type($filepath);
                    $filesize = $_FILES['gambar']['size'];

                    if (!array_key_exists($filetype, $allowedTypes)) {
                        showAlert("Format gambar tidak valid! Hanya JPG, PNG, dan GIF yang diizinkan.");
                    }
                    if ($filesize > 4 * 1024 * 1024) {
                        showAlert("Ukuran gambar tidak boleh lebih dari 4 MB!");
                    }

                    $dir = '../../assets/image/';
                    $newfilename = round(microtime(true)) . '.' . $allowedTypes[$filetype];
                    $target_path = $dir . $newfilename;

                    if (!move_uploaded_file($filepath, $target_path)) {
                        showAlert("Gagal mengupload gambar!");
                    }

                    if ($isEdit && file_exists($dir . $gambarLama)) {
                        unlink($dir . $gambarLama);
                    }

                    $data[] = $newfilename;
                } else {
                    $data[] = $isEdit ? $gambarLama : showAlert("Harap upload gambar!");
                }

                if ($isEdit) {
                    $data[] = $id;
                    $sql = "UPDATE mobil SET no_plat=?, merk=?, harga=?, deskripsi=?, status=?, gambar=?, updated_at=NOW() WHERE id_mobil=?";
                } else {
                    $sql = "INSERT INTO mobil (no_plat, merk, harga, deskripsi, status, gambar) VALUES (?, ?, ?, ?, ?, ?)";
                }

                $stmt = $koneksi->prepare($sql);
                $stmt->execute($data);

                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Data berhasil " . ($isEdit ? "diedit" : "ditambahkan") . "!'
                    }).then(() => {
                        window.location.href = 'mobil.php';
                        });
                        </script>";
                    }

                    if ($aksi === 'hapus' && !empty($_GET['id'])) {
                        $id = $_GET['id'];
                        $gambar = $_GET['gambar'];

                        if (file_exists("../../assets/image/$gambar")) {
                            unlink("../../assets/image/$gambar");
                        }

                        $stmt = $koneksi->prepare("DELETE FROM mobil WHERE id_mobil = ?");
                        $stmt->execute([$id]);

                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Data berhasil dihapus!'
                            }).then(() => {
                                window.location.href = 'mobil.php';
                                });
                                </script>";
                            }
                        }
                        ?>
