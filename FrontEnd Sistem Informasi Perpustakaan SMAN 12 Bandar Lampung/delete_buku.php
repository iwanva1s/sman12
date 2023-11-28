<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
    exit();
}

// Include database connection configuration
include 'koneksi.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the book number to be deleted
    $nomor = $_POST['nomor'];

    // Delete data from the database
    $query = "DELETE FROM buku WHERE nomor_buku = '$nomor'";

    if (mysqli_query($conn, $query)) {
        // Success
        echo "Data deleted successfully.";
    } else {
        // Error
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    exit(); // Stop execution after processing the form
}
?>