<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = ['status' => 'error', 'message' => 'User not logged in'];
    } else {
        $userId = $_SESSION['user_id'];  // Retrieve user ID from the session

        $field = mysqli_real_escape_string($conn, $_POST['field']);
        $newValue = mysqli_real_escape_string($conn, $_POST['newValue']);

        // Update the user data in the database
        $updateQuery = "UPDATE users SET $field = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'si', $newValue, $userId);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $response = ['status' => 'success'];
            } else {
                $response = ['status' => 'error', 'message' => mysqli_error($conn)];
            }

            mysqli_stmt_close($stmt);
        } else {
            $response = ['status' => 'error', 'message' => mysqli_error($conn)];
        }
    }

    // Close the database connection
    mysqli_close($conn);

    // Return JSON response
    echo json_encode($response);
} else {
    // Invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>