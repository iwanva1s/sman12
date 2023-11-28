<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        var_dump($user);  // Debugging
        if ($password == $user['password']) {
            echo "Login berhasil";  // Debugging
            $_SESSION['username'] = $username;
            header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/dashboard.php");
        } else {
            header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php?error=Password Salah");
        }
    } else {
        header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php?error=Pengguna Tidak Ditemukan");
    }
    

    $query->close();
    $conn->close();
}
?>
