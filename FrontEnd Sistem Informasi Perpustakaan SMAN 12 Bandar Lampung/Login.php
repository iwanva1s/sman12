<?php
session_start();

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/Dashboard.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
    .navbar-light .navbar-nav .nav-item:hover .nav-link {
        color: #ff0000;
    }

    .navbar-light .navbar-nav .nav-link {
        color: #FFFFFF;
    }

    body {
        background-color: #6499E9;
        font-family: Arial, Helvetica, sans-serif;
    }

    .container-welcome {
        display: flex;
        justify-content: center;
        /* Pusatkan secara horizontal */
        align-items: center;
        /* Pusatkan secara vertikal */
        flex-wrap: wrap;
        /* Biarkan elemen wrap jika tidak cukup ruang */
        margin-top: 3rem;
        margin-left: 15%;
    }

    .text-left {
        flex: 1;
        /* Bagian teks mengambil sebagian besar ruang */
        padding: 2rem;
        /* Berikan padding agar teks terlihat lebih baik */
    }

    .gambarbuku {
        flex: 1;
        /* Bagian gambar mengambil sebagian besar ruang */
        max-width: 100%;
        /* Maksimum lebar sesuai dengan konten parent */
        height: auto;
        margin-top: 2rem;
    }

    .container-login {
        max-width: 400px;
        width: 100%;
        padding: 20px;
        border-radius: 10px;
        margin-top: 2rem;
    }

    .container-login h1 {
        font-size: 30px;
        margin-bottom: 2rem;
        color: #ffffff;
        font-weight: bold;
    }

    .container-login input {
        width: 100%;
        height: 54px;
        border: 1px solid #D0E7D2;
        border-radius: 20px;
        margin-bottom: 20px;
        padding: 10px;
        box-sizing: border-box;
        background: #D0E7D2;
    }

    .container-login button {
        width: 100%;
        height: 40px;
        background: #3481F5;
        border: none;
        border-radius: 20px;
        color: #000000;
        font-size: 16px;
        cursor: pointer;
    }

    .container-login button:hover {
        background: #ff0000;
        color: #ffffff;
    }
    </style>
</head>

<body>

    <header class="custom-header" style="box-shadow: 0px 12px 4px rgba(0, 0, 0, 0.25); background-color: #9EDDFF;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="Home.php" style="display: flexbox; margin-left: 11%; scale: 0.7;">
                <img src="assets/dashboard/logo.png" alt="logo" class="logosma">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav"
                style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; text-decoration: none;">
                <strong>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Home.php" style="color: ;">Beranda</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="Login.php">Masuk</a>
                        </li>
                    </ul>
                </strong>
            </div>
        </nav>
    </header>

    <div class="container-welcome">
        <div class="text-left">
            <div class="container-login">
                <h1>Selamat Datang, Silahkan Masuk !</h1>
                <form action="../BackEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login_process.php"
                    method="post">
                    <label for="username"></label>
                    <input type="text" id="username" name="username" required placeholder="Username">

                    <label for="password"></label>
                    <input type="password" id="password" name="password" required placeholder="Password">

                    <button type="submit" style="color: #FFFFFF; font-weight: bold;">Masuk</button>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<p style="color: red; margin-top: 10px;">' . $_GET['error'] . '</p>';
                    }
                    ?>
                </form>
            </div>
        </div>
        <div class="gambarbuku-container">
            <!-- Masukkan gambar buku di sini -->
            <img src="assets/global/buku.png" alt="buku" class="img-fluid gambarbuku">
        </div>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


</body>

</html>