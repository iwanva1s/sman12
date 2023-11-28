<?php
session_start();

// Hapus semua session
session_destroy();

// Redirect ke halaman login
header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
exit();
?>
