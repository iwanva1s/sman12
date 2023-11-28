<?php
// update.php

// Assuming you have a database connection established in koneksi.php
include 'koneksi.php';

// Check if the ID and other necessary fields are set
if (isset($_POST['id']) && isset($_POST['nama'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    // Perform the update query
    $query = "UPDATE peminjaman SET nama = '$nama' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If the required fields are not set, return an error
    echo 'error';
}
?>