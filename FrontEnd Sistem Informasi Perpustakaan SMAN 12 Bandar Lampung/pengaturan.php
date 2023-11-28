<?php
session_start();

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
    exit();
}

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
    exit();
}

include 'koneksi.php';

// Fetch data from the "siswa" table
$querySiswa = "SELECT * FROM siswa";
$resultSiswa = mysqli_query($conn, $querySiswa);

// Check if the query was successful
if (!$resultSiswa) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch data from the "users" table for the logged-in user
$username = $_SESSION['username'];
$queryUser = "SELECT * FROM users WHERE username = '$username'";
$resultUser = mysqli_query($conn, $queryUser);

// Check if the query was successful
if (!$resultUser) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch user data as an associative array
$userData = mysqli_fetch_assoc($resultUser);

// Check if user data is not null before accessing it
if ($userData) {
    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle the case where no user data is found (optional)
    die("User data not found for username: $username");
}

if (isset($_POST['userId']) && isset($_POST['field']) && isset($_POST['newValue'])) {
    $userId = $_POST['userId'];
    $field = $_POST['field'];
    $newValue = $_POST['newValue'];

    // Update the user data in the database
    $updateQuery = "UPDATE users SET $field = '$newValue' WHERE id = $userId";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $response = ['status' => 'success'];
    } else {
        $response = ['status' => 'error', 'message' => mysqli_error($conn)];
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/global.css">
    <style>
    /* Style for custom popup */
    #customPopup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 15;
    }

    #customPopupContent {
        text-align: center;
    }

    #customPopupClose {
        cursor: pointer;
    }

    /* Ganti warna latar belakang untuk baris ganjil */

    #dataTable tbody tr:nth-child(odd) {
        background-color: #9EDDFF;
    }

    /* Ganti warna latar belakang untuk baris genap */

    #dataTable tbody tr:nth-child(even) {
        background-color: #F4F4F4;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Header -->
        <?php
        // Set a variable to be used in header.php
        $customHeaderContent = '<div class="halamansaatini">
                                    <a href="pengaturan.php">Pengaturan</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->

        <div class="content" id="pengaturanContent">
            <h1 style="font-size: 15px; position: absolute; left: 350px;  top: 110px;">
                Dashboard > Pengaturan
            </h1>

            <div class="judul_tbl" style=" position: absolute;
                        background-color: #9EDDFF;
                        width: 750px;
                        height: 75px;
                        left: 600px;
                        top: 165px;
                        font-weight: bold;
                        font-size: 20px;
                        display: flex;
                        align-items: center;">
                <table>
                    <thead>
                        <tr>
                            <th style="display:flex; flex: 1; margin-left: 30px;">Pengaturan Anggota</th>
                            <th
                                style="flex: 1; display: flex; justify-content: flex-start; position: absolute; top: 42px; left: 400px; transform: translate(-50%, -50%);">
                                <label for="search"
                                    style="position: relative; left :120px; display: inline-block; width: 150px;">
                                    <input type="search" name="search" id="search" placeholder="Search..."
                                        style="width: 100%; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);">
                                    <img src="assets/global/iconsearch.png" alt="Search Icon"
                                        style="position: absolute; left: 70px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
                                </label>
                            </th>
                            <th
                                style="width: 100px; text-align: center; align-items: center; position: absolute; top: 22px; right: 40px;">
                                <button id="tambah_anggota" onclick="openPopup()"
                                    style="width: 100%; height: 32px; border-radius: 20px; background-color: #6499E9; cursor: pointer; color: white; transition: background-color 0.3s; font-size: 15px; font-weight: bold;"
                                    onmouseover="this.style.backgroundColor='#ff0000'"
                                    onmouseout="this.style.backgroundColor='#6499E9'">
                                    Tambah
                                </button>
                            </th>
                        </tr>
                    </thead>
                </table>

                <div id="popupContainer"
                    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 5; background-image: url('assets/dashboard/bg\ popup.jpg'); background-size: cover; background-repeat: no-repeat; width: 450px; height: 600px;">
                    <!-- Popup Form Content -->
                    <form id="popupForm" action="process_form.php" method="post"
                        onsubmit="addRowToTable(); return false;" style="position: relative;">
                        <!-- Add your form fields here -->
                        <div style="display: flex; flex-direction: column; position:relative; top:25px;">

                            <label for="namasiswa" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Nama
                                Siswa</label>
                            <input type="text" id="namasiswa" name="namasiswa" required placeholder="Nama Siswa"
                                style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <div style="flex: 1;">
                                    <label for="nis" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">NIS</label>
                                    <input type="text" id="nis" name="nis" placeholder="NIS" required
                                        style="background-color: #f2f2f2; border-radius:20px; width: 100%;">
                                </div>
                                <div style="flex: 1;">
                                    <label for="kelas"
                                        style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Kelas</label>
                                    <input type="text" id="kelas" name="kelas" placeholder="Kelas" required
                                        style="background-color: #f2f2f2; border-radius:20px; width: 100%;">
                                </div>
                            </div>

                            <label for="no_hp" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Nomor HP</label>
                            <input type="text" id="no_hp" name="no_hp" placeholder="Nomor HP" required
                                style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                            <label for="alamat" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Alamat</label>
                            <input type="text" id="alamat" name="alamat" placeholder="Alamat" required
                                style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                            <label for="username" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Username</label>
                            <input type="text" id="username" name="username" placeholder="Username" required
                                style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                            <label for="password" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Password</label>
                            <input type="text" id="password" name="password" placeholder="Password" required
                                style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                            <!-- Close button (X) in the top-right corner -->
                            <button type="button" onclick="closePopup()"
                                style="position: absolute; bottom: 520px; right: 5px; background-color: #f2f2f2; border: none;">X</button>

                            <!-- Submit button centered at the bottom -->
                            <div style="display: flex; justify-content: center;">
                                <button type="submit"
                                    style="background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); width: 250px; height: 40px; color: black; border-radius: 20px;">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>







            </div>

        </div>

        <!-- Display user information in the "info_profil_box" section -->
        <div class="info_profil_box" style="
            position: absolute;
            background-color: #9EDDFF;
            width: 300px;
            height: 650px;
            left: 270px;
            top: 165px;
            display: flex;
            align-items: center;
            flex-direction: column;
        ">

            <!-- Circular profile picture -->
            <div
                style="border-radius: 50%; overflow: hidden; width: 150px; height: 150px; margin-bottom: 20px; margin-top: 20px; position: relative;">
                <input type="file" id="profileImageInput" style="display: none;" accept="image/*">
                <label for="profileImageInput" style="cursor: pointer; width: 100%; height: 100%;">
                    <img id="profileImage" src="assets/global/profil.png" alt="Profile Picture"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </label>

                <!-- Edit icon -->
                <img id="editIcon" src="assets/global/iconcamera.png" alt="Edit Icon"
                    style="position: absolute; top: 94px; right: 0; cursor: pointer; scale: 0.5">
            </div>

            <!-- Boxes for Name, Username, Email, and Password -->
            <!-- Box for Name -->
            <div
                style="width: 80%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

                <!-- Name -->
                <label for="name-input">
                    <strong>Nama:</strong>
                </label>
                <div id="name-container" style="margin-bottom: 10px;">
                    <span id="name-text"><?php echo $userData['nama']; ?></span>
                    <input type="text" id="name-input" style="display: none;" value="<?php echo $userData['nama']; ?>">
                </div>

                <!-- Edit icon -->
                <img src="assets/global/iconedit.png" alt="Edit Icon"
                    onclick="enableEdit('name-text', 'name-input', disableNameEdit)"
                    style="cursor: pointer; transform: scale(0.5); position: absolute; right: 5px; top: 35%; transform: translateY(-50%); scale: 0.6">
            </div>

            <!-- Box for Username -->
            <div
                style="width: 80%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

                <!-- Username -->
                <label for="username-input">
                    <strong>Username:</strong>
                </label>
                <div id="username-container" style="margin-bottom: 10px;">
                    <span id="username-text"><?php echo $userData['username']; ?></span>
                    <input type="text" id="username-input" style="display: none;"
                        value="<?php echo $userData['username']; ?>">
                </div>

                <!-- Edit icon -->
                <img src="assets/global/iconedit.png" alt="Edit Icon"
                    onclick="enableEdit('username-text', 'username-input', disableUsernameEdit)"
                    style="cursor: pointer; transform: scale(0.5); position: absolute; right: 5px; top: 35%; transform: translateY(-50%); scale: 0.6">
            </div>

            <!-- Box for Email -->
            <div
                style="width: 80%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

                <!-- Email -->
                <label for="email-input">
                    <strong>Email:</strong>
                </label>
                <div id="email-container" style="margin-bottom: 10px;">
                    <span id="email-text"><?php echo $userData['email']; ?></span>
                    <input type="text" id="email-input" style="display: none;"
                        value="<?php echo $userData['email']; ?>">
                </div>

                <!-- Edit icon -->
                <img src="assets/global/iconedit.png" alt="Edit Icon"
                    onclick="enableEdit('email-text', 'email-input', disableEmailEdit)"
                    style="cursor: pointer; transform: scale(0.5); position: absolute; right: 5px; top: 35%; transform: translateY(-50%); scale: 0.6">
            </div>

            <!-- Box for Password -->
            <div
                style="width: 80%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

                <!-- Password -->
                <label for="password-input">
                    <strong>Password:</strong>
                </label>
                <div id="password-container" style="margin-bottom: 10px;">
                    <span id="password-text"><?php echo $userData['password']; ?></span>
                    <input type="password" id="password-input" style="display: none;"
                        value="<?php echo $userData['password']; ?>">
                </div>

                <!-- Edit icon -->
                <img src="assets/global/iconedit.png" alt="Edit Icon"
                    onclick="enableEdit('password-text', 'password-input', disablePasswordEdit)"
                    style="cursor: pointer; transform: scale(0.5); position: absolute; right: 5px; top: 35%; transform: translateY(-50%); scale: 0.6;">
            </div>

        </div>

        <table id="dataTable"
            style="position: absolute; width: 745px; left: 600px; top: 240px; border-collapse: collapse;">
            <!-- Header Row -->
            <thead>
                <tr style="background-color: #F4F4F4; height: 50px;">
                    <!-- Kolom "no" -->
                    <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">No</th>

                    <!-- Kolom "nama siswa" -->
                    <th style="width: 30%; text-align: center; padding-right: 10px; font-weight: bold;">Nama Siswa
                    </th>

                    <!-- Kolom "nis" -->
                    <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">NIS</th>

                    <!-- Kolom "kelas" -->
                    <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Kelas
                    </th>

                    <!-- Kolom "no_hp" -->
                    <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">Nomor HP
                    </th>

                    <!-- Kolom "alamat" -->
                    <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Alamat</th>

                    <!-- Kolom "username" -->
                    <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Username
                    </th>

                    <!-- Kolom "password" -->
                    <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">Password
                    </th>

                    <!-- Kolom "aksi" -->
                    <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Aksi</th>
                </tr>
            </thead>

            <!-- Display siswa data -->
            <tbody>
                <?php
        $no = 1; // Initialize a variable to store the sequential number
        while ($rowSiswa = mysqli_fetch_assoc($resultSiswa)) {
            $rowColor = ($no % 2 == 0) ? '#F4F4F4' : '#9EDDFF'; // Set background color based on row number
            echo "<tr id='row-{$rowSiswa['id']}' style='background-color: {$rowColor};'>"; 

            echo "<td style='text-align: center;'>{$no}</td>"; // Display the sequential number
            echo "<td style='text-align: center;'>{$rowSiswa['namasiswa']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['nis']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['kelas']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['no_hp']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['alamat']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['username']}</td>";
            echo "<td style='text-align: center;'>{$rowSiswa['password']}</td>";
            echo "<td style='text-align: center; padding: 10px; display: flex; justify-content: center;'>
                    <img src='assets/dashboard/iconedit.png' alt='Simpan' style='cursor:pointer; transform: scale(0.7);' onclick='ubahData(this)'>
                    <img src='assets/global/iconhapus.png' alt='Hapus' style='cursor:pointer; transform: scale(0.7);' onclick='hapusData(this)'>
                </td>";

            echo "</tr>";
            $no++; // Increment the sequential number for the next row
        }
    ?>
            </tbody>


        </table>



        <!-- Popup -->
        <?php include 'popup.php'; ?>

        <div id="customPopup">
            <div id="customPopupContent"></div>
        </div>
    </div>

    <script src=" https://code.jquery.com/jquery-3.2.1.slim.min.js "></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
    <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
    <script src=" script/global.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    function openPopup() {
        // Show the popup container
        document.getElementById('popupContainer').style.display = 'block';
    }

    function closePopup() {
        // Hide the popup container
        document.getElementById('popupContainer').style.display = 'none';
    }

    function openCustomPopup(message) {
        document.getElementById('customPopupContent').innerHTML = message;
        document.getElementById('customPopup').style.display = 'block';
    }

    function closeCustomPopup() {
        document.getElementById('customPopup').style.display = 'none';
    }

    function addRowToTable() {
        // Submit the form using AJAX
        $.ajax({
            type: 'POST',
            url: 'add_siswa.php',
            data: $('#popupForm').serialize(), // Serialize form data
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    openCustomPopup('Akun Siswa Berhasil Ditambahkan');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    openCustomPopup('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });

        // Close the popup (if needed)
        closePopup();
    }

    function hapusData(element) {
        // Get the row data
        var row = element.closest('tr');
        var id = row.id.split('-')[1]; // Extract the ID from the row ID attribute

        // Confirm deletion
        if (confirm('Hapus data siswa ' + row.cells[1].innerText + '?')) {
            // Perform deletion using AJAX
            $.ajax({
                type: 'POST',
                url: 'delete_siswa.php', // Replace with the actual PHP file to handle deletion
                data: {
                    id: id
                }, // Send the ID to the server
                success: function(response) {
                    if (response.status === 'success') {
                        row.remove();
                        openCustomPopup('Data siswa berhasil dihapus');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        openCustomPopup('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                }
            });
        }
    }
    </script>

    <!-- Add these functions to your existing JavaScript code -->
    <script>
    function enableEdit(textId, inputId, disableFunction) {
        var textElement = document.getElementById(textId);
        var inputElement = document.getElementById(inputId);

        textElement.style.display = 'none';
        inputElement.style.display = 'inline-block';
        inputElement.value = textElement.innerText;
        inputElement.focus();

        // Attach keyup event to the input element
        inputElement.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                // Handle Enter key press
                disableFunction(textId, inputId);
                updateData(textId, inputId, inputElement.value); // Call the function to update data
            }
        });
    }

    function disableNameEdit(textId, inputId) {
        var textElement = document.getElementById(textId);
        var inputElement = document.getElementById(inputId);

        textElement.innerText = inputElement.value;
        textElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
    }

    function disableUsernameEdit(textId, inputId) {
        var textElement = document.getElementById(textId);
        var inputElement = document.getElementById(inputId);

        textElement.innerText = inputElement.value;
        textElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
    }

    function disableEmailEdit(textId, inputId) {
        var textElement = document.getElementById(textId);
        var inputElement = document.getElementById(inputId);

        textElement.innerText = inputElement.value;
        textElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
    }

    function disablePasswordEdit(textId, inputId) {
        var textElement = document.getElementById(textId);
        var inputElement = document.getElementById(inputId);

        textElement.innerText = inputElement.value;
        textElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
    }

    function updateData(textId, inputId, newValue) {
        var userId = <?php echo $userData['id']; ?>;

        $.ajax({
            type: 'POST',
            url: 'update_user_data.php',
            data: {
                userId: userId,
                field: inputId,
                newValue: newValue
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Data updated successfully');
                } else if (response.status === 'error') {
                    console.error('Error updating data:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }
    </script>





</body>

</html>