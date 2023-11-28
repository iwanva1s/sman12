<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming 'id' is the parameter passed from the AJAX request
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if ($id) {
        // Perform the deletion query
        $deleteQuery = "DELETE FROM siswa WHERE id = $id";

        if (mysqli_query($conn, $deleteQuery)) {
            // Deletion successful
            $response = ['status' => 'success'];
        } else {
            // Deletion failed
            $response = ['status' => 'error', 'message' => mysqli_error($conn)];
        }
    } else {
        // Invalid or missing ID parameter
        $response = ['status' => 'error', 'message' => 'Invalid or missing ID parameter'];
    }

    // Close the database connection
    mysqli_close($conn);

    // Send JSON response back to the AJAX request
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If the request method is not POST
    http_response_code(405); // Method Not Allowed
    echo 'Method not allowed';
}
?>