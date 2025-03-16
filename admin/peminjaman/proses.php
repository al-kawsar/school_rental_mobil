<?php
/*
  | Source Code Aplikasi Rental Mobil PHP & MySQL
  | 
  | @package   : rental_mobil
  | @file	   : proses.php 
  | @author    : alkawsar / Andi Muh Raihan Alkawsar
  | @copyright : Copyright (c) 2017-2021 Codekop.com (https://www.codekop.com)
  | @blog      : https://www.codekop.com/products/source-code-aplikasi-rental-mobil-php-mysql-7.html 
  | 
  | 
  | 
  | 
 */
  require_once '../../koneksi/koneksi.php';

  if ($_GET['id'] == 'konfirmasi') {
    $data2[] = $_POST['status'];
    $data2[] = $_POST['id_mobil'];
    $sql2 = "UPDATE `mobil` SET `status`= ? WHERE id_mobil= ?";
    $row2 = $koneksi->prepare($sql2);
    $row2->execute($data2);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Status Mobil berhasil diperbarui',
      showConfirmButton: false,
      timer: 1500
      }).then(() => {
        history.go(-1);
        });
        </script>";
      }
