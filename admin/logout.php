<?php
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login atau halaman utama
header("Location: ../index.php?logout=success");
exit;
?>
