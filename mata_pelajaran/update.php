<?php
require_once('../db/database.php'); // Koneksi ke database
session_start();

// Ambil ID pelajaran dari URL
$id_pelajaran = $_GET['id_pelajaran'] ?? null;
$successMessage = "";
$errors = [];

if ($id_pelajaran) {
    // Query untuk mengambil data mata pelajaran
    $stmt = $conn->prepare("SELECT * FROM mata_pelajaran WHERE id_pelajaran = ?");
    $stmt->bind_param("i", $id_pelajaran);
    $stmt->execute();
    $result = $stmt->get_result();
    $mataPelajaran = $result->fetch_assoc();

    if (!$mataPelajaran) {
        die("Mata pelajaran tidak ditemukan.");
    }

    // Jika form disubmit
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_pelajaran'])) {
        $nama_pelajaran = trim($_POST['nama_pelajaran']);
        $deskripsi = trim($_POST['deskripsi']);

        // Validasi input
        if (empty($nama_pelajaran)) {
            $errors[] = "Nama pelajaran tidak boleh kosong.";
        }
        if (empty($deskripsi)) {
            $errors[] = "Deskripsi tidak boleh kosong.";
        }

        // Jika validasi lolos, lakukan update
        if (count($errors) === 0) {
            $updateStmt = $conn->prepare("UPDATE mata_pelajaran SET nama_pelajaran = ?, deskripsi = ? WHERE id_pelajaran = ?");
            $updateStmt->bind_param("ssi", $nama_pelajaran, $deskripsi, $id_pelajaran);

            if ($updateStmt->execute()) {
                $successMessage = "Mata pelajaran berhasil diperbarui!";
                // Redirect jika berhasil update
                header("Location: read.php");
                exit();
            } else {
                $errors[] = "Error: " . $updateStmt->error;
            }

            $updateStmt->close();
        }
    }
} else {
    die("ID pelajaran tidak diberikan.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mata Pelajaran</title>

    <!-- Link Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- End Boostrap -->

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- End SweetAlert -->

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
        }


        .btn-primary:hover {
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



    <div class="container-fluid position-absolute top-50 start-50 translate-middle">

        <h2 class="mb-4">Pilih Mata Pelajaran untuk Diupdate</h2>

        <form action="" method="POST" class="form-floating">
            <!-- Input ID Pelajaran -->
            <div class="mb-3">
                <input type="hidden" name="id_pelajaran"
                    value="<?php echo htmlspecialchars($mataPelajaran['id_pelajaran']); ?>">
            </div>
            <!-- Input Nama Pelajaran -->
            <div class="mb-3">
                <label for="nama_pelajaran" class="form-label">Nama Pelajaran</label>
                <input type="text" id="nama_pelajaran" name="nama_pelajaran" class="form-control"
                    value="<?php echo htmlspecialchars($mataPelajaran['nama_pelajaran']); ?>" required>
            </div>
            <!-- Input Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control"
                    required><?php echo htmlspecialchars($mataPelajaran['deskripsi']); ?></textarea>
            </div>
            <!-- Tombol Update -->
            <button type="submit" name="update_pelajaran" class="btn btn-primary">Edit Pelajaran</button>
        </form>
    </div>

    <!-- Link JS Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- End Link JS Boostrap -->

    <<?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-3">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

</body>

</html>