<?php
session_start();

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("location: ../FrontEnd Sistem Informasi Perpustakaan SMAN 12 Bandar Lampung/login.php");
    exit();
}

// Include database connection configuration
include 'koneksi.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nomor = $_POST['nomor'];
    $judul = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $pengarang = $_POST['pengarang'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $tahun = $_POST['tahun'];

    // Handle file upload for "sampul" field
    $sampulDir = "path/to/your/image/";
    $sampulPath = $sampulDir . basename($_FILES['sampul']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($sampulPath, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["sampul"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["sampul"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["sampul"]["tmp_name"], $sampulPath)) {
            echo "The file " . htmlspecialchars(basename($_FILES["sampul"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert data into the database
    $query = "INSERT INTO buku (nomor_buku, judul_buku, penerbit, pengarang, kategori, jumlah, tahun, sampul) VALUES ('$nomor', '$judul', '$penerbit', '$pengarang', '$kategori', '$jumlah', '$tahun', '$sampulPath')";

    if (mysqli_query($conn, $query)) {
        // Success
        echo "Data inserted successfully.";
    } else {
        // Error
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    exit(); // Stop execution after processing the form
}

// Fetch data from the database
$query = "SELECT * FROM buku";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
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
    <title>Buku</title>
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
                                    <a href="buku.php">Data Buku</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->
        <div class="content" id="bukuContent">
            <h1 style="font-size: 15px; position: absolute; left: 350px; top: 110px;">
                Dashboard > Buku
            </h1>


            <form id="caribukuform" style="position: absolute; left: 681px; top: 184px;">
                <label for="search" style="position: relative; display: inline-block;">
                    <input type="search" name="search" id="search" placeholder="Search ..."
                        style="width: 300px; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);">
                    <img src="assets/global/iconsearch.png" alt="Search Icon"
                        style="position: absolute; left: 210px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
                </label>
            </form>

            <!-- "Tambah Buku" Button -->
            <button id="tambahbukubtn" onclick="openPopup()" style=" position: absolute; 
                        width: 130px;
                        height: 32px;
                        border-radius: 20px;
                        left: 1030px;
                        top: 184px;
                        background-color: #6499E9;
                        cursor: pointer;
                        color: white; /* Set text color to white */
                        transition: background-color 0.3s; /* Add transition for smooth color change */
                        " onmouseover="this.style.backgroundColor='#ff0000'"
                onmouseout="this.style.backgroundColor='#6499E9'">
                Tambah Buku
            </button>

            <!-- Popup Container -->
            <div id="popupContainer" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index:5; height:550px; background-image: url('assets/dashboard/bg\ popup.jpg');
      background-size: cover; /* Untuk memastikan gambar selalu menutupi seluruh latar belakang */
      background-repeat: no-repeat; /* Menghindari pengulangan gambar di latar belakang */">
                <!-- Popup Form Content -->
                <form id="popupForm" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; align-items: center;">
                    <!-- Add your form fields here -->
                    <label for="nomor"></label>
                    <input type="text" id="nomorInput" name="nomor" placeholder="Nomor Buku" required
                        style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>

                    <label for="judul"></label>
                    <input type="text" id="judulInput" name="judul" placeholder="Judul Buku " required
                        style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>

                    <label for="penerbit"></label>
                    <input type="text" id="penerbitInput" name="penerbit" placeholder="Penerbit" required
                        style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>

                    <label for="pengarang"></label>
                    <input type="text" id="pengarangInput" name="pengarang" placeholder="Pengarang" required
                        style="background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>

                    <div style="display: flex; justify-content: space-between; width: 100%;">
                        <div style="flex: 1; margin-right: 10px;">
                            <label for="kategori"></label>
                            <select id="kategoriInput" name="kategori" required>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="fiksi">Fiksi</option>
                                <option value="nonfiksi">Nonfiksi</option>
                            </select><br>
                        </div>
                        <div style="flex: 1; margin-left: 10px;">
                            <label for="tahun"></label>
                            <input type="text" id="tahunInput" name="tahun" placeholder="Tahun" required
                                style="background-color: #f2f2f2; border-radius:20px;"><br>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; width: 100%;">
                        <div style="flex: 1; margin-right: 10px;">
                            <label for="jumlah"></label>
                            <input type="text" id="jumlahInput" name="jumlah" placeholder="Jumlah" required
                                style="background-color: #f2f2f2; border-radius:20px;"><br>
                        </div>
                        <div style="flex: 1; margin-left: 10px;">
                            <label for="sampul"></label>
                            <input type="file" id="sampulInput" name="sampul" accept="image/*" required><br>
                        </div>
                    </div>

                    <button type="submit"
                        style="width: 430px; height: 50px; background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px;">Simpan</button>

                    <button type="button" onclick="closePopup()"
                        style="position: absolute; top: 10px; right: 10px; font-size: 20px; cursor: pointer;">X</button>
                </form>


            </div>




            <!-- Table -->
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
                        <!-- Kolom "no" -->
                        <td style="text-align: center; padding: 10px;">No</td>

                        <!-- Kolom "sampul" -->
                        <td style="text-align: center; padding: 10px;">Sampul</td>

                        <!-- Kolom "judul" -->
                        <td style="text-align: center; padding: 10px;">Judul</td>

                        <!-- Kolom "nomor" -->
                        <td style="text-align: center; padding: 10px;">Nomor</td>

                        <!-- Kolom "kategori" -->
                        <td style="text-align: center; padding: 10px;">Kategori</td>

                        <!-- Kolom "jumlah" -->
                        <td style="text-align: center; padding: 10px;">Jumlah</td>

                        <!-- Kolom "aksi" -->
                        <td style="text-align: center; padding: 10px;">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
    // Display data in the table
    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $background_color = $count % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

        echo "<tr style='background-color: $background_color;'>
                <td style='text-align: center; padding: 10px;'>$count</td>
                <td style='text-align: center; padding: 10px;'><img src='path/to/your/image/{$row['sampul']}' alt='Sampul Buku' style='width: 50px;'></td>
                <td style='text-align: center; padding: 10px;'>{$row['judul_buku']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['nomor_buku']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['kategori']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['jumlah']}</td>
                <td style='text-align: center; padding: 10px;'>
                    <button type=\"button\" onclick=\"ubahData(this)\">
                        <img src=\"assets/dashboard/iconedit.png\" alt=\"Simpan\" style=\"cursor:pointer; transform: scale(0.7);\">
                    </button>
                    <button type=\"button\" onclick=\"delete_buku(this)\" data-id=\"{$row['nomor_buku']}\">
                        <img src=\"assets/global/iconhapus.png\" alt=\"Hapus\" style=\"cursor:pointer; transform: scale(0.7);\">
                    </button>
                    <!-- ... -->
                </td>
            </tr>";
    }
    ?>
                </tbody>



            </table>


            <!-- Popup -->
            <?php include 'popup.php'; ?>
        </div>

        <script src=" https://code.jquery.com/jquery-3.2.1.slim.min.js "></script>
        <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
        <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
        <script src=" script/global.js">
        </script>
        <script>
        function openPopup() {
            // Show the popup container
            document.getElementById('popupContainer').style.display = 'block';
        }

        function closePopup() {
            // Hide the popup container
            document.getElementById('popupContainer').style.display = 'none';
        }
        </script>


        <!-- ... (Your existing HTML code) ... -->
        <script>
        let rowCount = 0;

        document.getElementById('popupForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get form data
            var formData = new FormData(this);

            // Use AJAX to submit the form data to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'buku.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Success
                    // Reload the page after a short delay (you can adjust the delay as needed)
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    // Error
                    alert('Error: ' + xhr.status);
                }
            };
            xhr.send(formData);
        });
        </script>

        <script>
        function delete_buku(button) {
            // Get the book number from the data-nomor attribute
            var nomor = button.getAttribute('data-nomor');

            // Confirm deletion
            var confirmDelete = confirm('Apakah Anda yakin ingin menghapus buku ini?');

            if (confirmDelete) {
                // Use AJAX to submit the form data to the server
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_buku.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Set up the data to be sent
                var data = 'nomor=' + encodeURIComponent(nomor);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Success
                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        // Error
                        alert('Error: ' + xhr.status);
                    }
                };
                xhr.send(data);
            }
        }
        </script>





        <!-- ... (Your existing JavaScript code) ... -->




</body>

</html>