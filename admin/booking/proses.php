<?php

require_once '../../koneksi/koneksi.php';

if ($_GET['id'] == 'konfirmasi') {
  $data2[] = $_POST['status'];
  $data2[] = $_POST['id_booking'];
  $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`= ? WHERE id_booking= ?";
  $row2 = $koneksi->prepare($sql2);
  $row2->execute($data2);

  echo "
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Kirim Sukses, Pembayaran berhasil!',
    showConfirmButton: true
    }).then(() => {
      history.go(-1);
      });
      </script>";
    }
