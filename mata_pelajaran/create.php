<?php
require_once('../db/database.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_pelajaran'])) {
    $nama_pelajaran = $_POST['nama_pelajaran'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO mata_pelajaran (nama_pelajaran, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_pelajaran, $deskripsi);

    if ($stmt->execute()) {
        echo "Mata pelajaran berhasil ditambahkan.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Link Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Link SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #FFBD59, #3A89FF);
            /* Gradient latar belakang */
            height: 100vh;
            color: #333;
        }

        .container-fluid {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px 40px;
            width: 90%;
            max-width: 400px;
            display: flex;
            position: relative;
        }

        .col-12 {
            justify-content: center;
            align-items: center;
        }

        .btn:hover {
            background-color: #3A89FF;
            /* Warna saat hover diubah ke #3A89FF */
            color: #fff;
            /* Tetap putih saat hover */
            transform: translateY(-2px);
            /* Efek sedikit naik saat hover */
        }
    </style>

</head>

<body>

    <!-- <form method="POST">
        <div class="mb-3">
            <input type="text" class="form-control" id="floatingInput" name="nama_pelajaran"
                placeholder="Nama Pelajaran" required>
            <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
            <button type="submit" name="create_pelajaran">Tambah Pelajaran</button>
    </form> -->

    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <form class="row" method="POST">
            <div class="mb-3">
                <label for="form-control" class="form-label">Nama Pelajaran</label>
                <input type="text" class="form-control" id="floatingInput" name="nama_pelajaran"
                    placeholder="Bahasa Inggris" required>
            </div>
            <div class="mb-3">
                <div class="col-12">
                    <label for="floatingTextarea">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" placeholder="Tambahkan Deskripsi"
                        id="floatingTextarea"></textarea>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-warning" name="create_pelajaran">Tambah Pelajaran</button>
            </div>
        </form>
    </div>


    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- End JS Bootstrap -->

</body>

</html>