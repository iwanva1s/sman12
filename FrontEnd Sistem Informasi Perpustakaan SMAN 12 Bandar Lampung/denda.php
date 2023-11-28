<?php
session_start();

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
    exit();
}

include 'koneksi.php';

// Fetch data from the "peminjaman" table
$queryDenda = "SELECT * FROM denda";
$resultDenda = mysqli_query($conn, $queryDenda);

// Check if the query was successful
if (!$resultDenda) {
    die("Query failed: " . mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/global.css">
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Header -->
        <?php
        // Set a variable to be used in header.php
        $customHeaderContent = '<div class="halamansaatini">
                                    <a href="denda.php">Denda</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->

    </div>

    <div class="content" id="dendaContent">
        <h1 style=" font-size: 15px; position: absolute; left: 350px;  top: 110px;">
            Dashboard > Denda
        </h1>

        <h2 style="font-size: 15px; position: absolute; left: 350px; top: 240px;">
            Denda Keterlambatan
        </h2>


        <form id="carinamaform" style="position: absolute; left: 881px; top: 184px;">
            <label for="search" style="position: relative; display: inline-block;">
                <input type="search" name="search" id="search" placeholder="Search ..."
                    style="width: 300px; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);">
                <img src="assets/global/iconsearch.png" alt="Search Icon"
                    style="position: absolute; left: 210px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
            </label>
        </form>

        <button style=" position: absolute; 
                        width: 130px;
                        height: 32px;
                        border-radius: 20px;
                        left: 1040px;
                        top: 240px;
                        background-color: #6499E9;
                        cursor: pointer;
                        color: white; /* Set text color to white */
                        transition: background-color 0.3s; /* Add transition for smooth color change */
                        display: flex;
                        font-size: 12px;
                        font-weight: bold;
                        align-items: center;
        " onmouseover="this.style.backgroundColor='#ff0000'" onmouseout="this.style.backgroundColor='#6499E9'"
            onclick="window.location.href='riwayatdenda.php';">
            <img src="assets/global/icon history.png" alt="Tambah Buku"
                style="width: 18px; height: 27px; margin-right: 5px;">
            <span>Lihat Riwayat</span>
        </button>


        <table style="position: absolute;
              background-color: #9EDDFF;
              border-collapse: collapse;
              border-top-left-radius: 20px;
              border-top-right-radius: 20px;
              width: 838px;
              left: 350px;
              top: 280px;
              font-weight: bold;
              font-size: 20px;">
            <thead>
                <tr>
                    <!-- Kolom "No" -->
                    <td style="text-align: center; padding: 10px;">No</td>

                    <!-- Kolom "Nama" -->
                    <td style="text-align: center; padding: 10px;">Nama</td>

                    <!-- Kolom "Judul" -->
                    <td style="text-align: center; padding: 10px;">Judul</td>

                    <!-- Kolom "Start" -->
                    <td style="text-align: center; padding: 10px;">Start</td>

                    <!-- Kolom "Tenggat" -->
                    <td style="text-align: center; padding: 10px;">Tenggat</td>

                    <!-- Kolom "Total" -->
                    <td style="text-align: center; padding: 10px;">Total</td>

                    <!-- Kolom "Status" -->
                    <td style="text-align: center; padding: 10px;">Status</td>

                    <!-- Kolom "Aksi" -->
                    <td style="text-align: center; padding: 10px;">Aksi</td>

                </tr>
            </thead>
            <tbody>
                <?php

  $count = 0;
// Inside the while loop
while ($rowDenda = mysqli_fetch_assoc($resultDenda)) {
      $count++;
    $background_color = $count % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    echo "<tr style='background-color: $background_color;'>";
    echo "<td style='text-align: center; padding: 10px;'>$count</td>";
    echo "<td style='text-align: center;'>{$rowDenda['nama']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['judul']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['start']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['tenggat']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['total']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['status']}</td>";

    // New column for actions
echo "<td style='text-align: center;'>";
echo "<select id='aksiSelect{$rowDenda['id']}' onchange='changeStatus({$rowDenda['id']})'>";
    
// Set the selected option based on the current status
if ($rowDenda['status'] == 'Lunas') {
    echo "<option value='Konfirmasi_Lunas' selected>Lunas</option>";
    echo "<option value='Konfirmasi_Belum_Lunas'>Belum Lunas</option>";
} else {
    echo "<option value='Konfirmasi_Lunas'>Lunas</option>";
    echo "<option value='Konfirmasi_Belum_Lunas' selected>Belum Lunas</option>";
}

echo "</select>";
echo "<button onclick='executeAction({$rowDenda['id']})'>OK</button>";
echo "</td>";


}

?>
            </tbody>
        </table>
    </div>


    <!-- Popup -->
    <?php include 'popup.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="script/global.js"></script>
    <script>
    function changeStatus(dendaId) {
        console.log("Changing status for Denda ID: " + dendaId);
        var selectedValue = $("#aksiSelect" + dendaId).val();
        console.log("Selected value: " + selectedValue);
        var selectedValue = $("#aksiSelect" + dendaId).val();

        // You can perform an AJAX request to update the status in the database
        // For simplicity, I'll use jQuery for the AJAX request


        // AJAX request
        $.ajax({
            type: "POST",
            url: "update_status.php", // Change this to the actual file handling the update
            data: {
                dendaId: dendaId,
                newStatus: selectedValue
            },
            success: function(response) {
                // Update the displayed status in the table
                alert("Denda ID: " + dendaId + ", New Status: " + selectedValue);
                location.reload(); // Refresh the page after updating
            },
            error: function(error) {
                console.error("Error updating status: ", error);
            }
        });
    }

    function executeAction(dendaId) {
        // You can perform additional actions here
        alert("Executing action for Denda ID: " + dendaId);
    }
    </script>



</body>

</html>