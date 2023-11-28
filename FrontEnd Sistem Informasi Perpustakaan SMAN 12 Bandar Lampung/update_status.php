<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dendaId = $_POST["dendaId"];
    $newStatus = $_POST["newStatus"];

    // Update the status in the database
    $updateQuery = "UPDATE denda SET status = '$newStatus' WHERE id = $dendaId";
    $updateResult = mysqli_query($conn, $updateQuery);

    if (!$updateResult) {
        die("Update failed: " . mysqli_error($conn));
    }

    echo "Update successful";
    mysqli_close($conn);
} else {
    echo "Invalid request";
}
?>