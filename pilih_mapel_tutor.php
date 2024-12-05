<?php
session_start();
include('db/database.php'); // Koneksi ke database

// Cek apakah user sudah login dan perannya adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Query untuk mengambil daftar tutor
$tutor_stmt = $pdo->query("SELECT id_tutor, nama FROM tutor");
$tutors = $tutor_stmt->fetchAll(PDO::FETCH_ASSOC);

// Query untuk mengambil daftar mata pelajaran
$mapel_stmt = $pdo->query("SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran");
$mata_pelajaran = $mapel_stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses simpan data tutor-pelajaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $id_tutor = $_POST['id_tutor'];
    $id_pelajaran = $_POST['id_pelajaran'];
    $status = 'pending'; // Default status adalah pending

    // Cek apakah tutor sudah terdaftar untuk mata pelajaran yang sama
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM tutor_pelajaran WHERE id_tutor = :id_tutor AND id_pelajaran = :id_pelajaran");
    $check_stmt->execute(['id_tutor' => $id_tutor, 'id_pelajaran' => $id_pelajaran]);
    $count = $check_stmt->fetchColumn();

    // Jika sudah terdaftar, beri pesan error
    if ($count > 0) {
        echo "Tutor ini sudah terdaftar untuk mata pelajaran ini.";
    } else {
        // Query untuk mengambil nama tutor berdasarkan id_tutor
        $stmt_tutor = $pdo->prepare("SELECT nama FROM tutor WHERE id_tutor = :id_tutor");
        $stmt_tutor->execute(['id_tutor' => $id_tutor]);
        $tutor = $stmt_tutor->fetch(PDO::FETCH_ASSOC);

        // Query untuk mengambil nama mata pelajaran berdasarkan id_pelajaran
        $stmt_mapel = $pdo->prepare("SELECT nama_pelajaran FROM mata_pelajaran WHERE id_pelajaran = :id_pelajaran");
        $stmt_mapel->execute(['id_pelajaran' => $id_pelajaran]);
        $mapel = $stmt_mapel->fetch(PDO::FETCH_ASSOC);

        // Jika data ditemukan, masukkan ke dalam tutor_pelajaran
        if ($tutor && $mapel) {
            $stmt_insert = $pdo->prepare("INSERT INTO tutor_pelajaran (id_tutor, nama, id_pelajaran, nama_pelajaran, status) 
                                         VALUES (:id_tutor, :nama, :id_pelajaran, :nama_pelajaran, :status)");
            $stmt_insert->execute([
                'id_tutor' => $id_tutor,
                'nama' => $tutor['nama'],
                'id_pelajaran' => $id_pelajaran,
                'nama_pelajaran' => $mapel['nama_pelajaran'],
                'status' => $status
            ]);
            echo "Data berhasil dimasukkan.";
        } else {
            echo "Data tutor atau mata pelajaran tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Tutor Mata Pelajaran</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Tambahkan path CSS -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Tambah Tutor Mata Pelajaran</h1>

        <!-- Form untuk menambah tutor dan mata pelajaran -->
        <form action="pilih_mapel_tutor.php" method="POST">
            <div class="form-group">
                <label for="id_tutor">Pilih Tutor:</label>
                <select name="id_tutor" id="id_tutor" class="form-control">
                    <option value="">-- Pilih Tutor --</option>
                    <?php foreach ($tutors as $tutor): ?>
                        <option value="<?= $tutor['id_tutor']; ?>"><?= htmlspecialchars($tutor['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_pelajaran">Pilih Mata Pelajaran:</label>
                <select name="id_pelajaran" id="id_pelajaran" class="form-control">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php foreach ($mata_pelajaran as $mapel): ?>
                        <option value="<?= $mapel['id_pelajaran']; ?>"><?= htmlspecialchars($mapel['nama_pelajaran']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block">Simpan Data</button>
        </form>
    </div>
</body>
</html>
