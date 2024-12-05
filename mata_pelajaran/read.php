<?php
require_once('../db/database.php'); // Koneksi ke database
session_start();

// Ambil data semua mata pelajaran dari database
$result = $conn->query("SELECT * FROM mata_pelajaran");
if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Mata Pelajaran</title>

    <!-- Link Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

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

    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <h2 class="mb-4 row">Pilih Mata Pelajaran untuk Diupdate</h2>
        <form method="GET" action="update.php" class="form-group dropdown">
            <div class="mb-4">
                <label for="id_pelajaran" class="form-label">Mata Pelajaran:</label>
                <select id="id_pelajaran" name="id_pelajaran" class="form-select btn-group" required>
                    <option value="">Pilih Mata Pelajaran</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id_pelajaran']); ?>">
                            <?php echo htmlspecialchars($row['nama_pelajaran']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Edit Pelajaran</button>
        </form>
    </div>



    <!-- Link JS Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- End JS Boostrap -->

</body>

</html>