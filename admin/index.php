<?php
require '../koneksi/koneksi.php';
$title_web = 'Dashboard';
include 'header.php';
if(empty($_SESSION['USER'])) {
    session_start();
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<div class="container mt-4">
</div>

<?php include 'footer.php'; ?>