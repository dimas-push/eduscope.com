<?php
session_start();
include('db.php');

// Cek apakah pelajar sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'pelajar') {
    header("Location: dashboard.php");
    exit();
}

$id_pelajar = $_SESSION['id_user']; // ID pelajar dari sesi

// Ambil data semua mata pelajaran dari database
$query = "SELECT * FROM mata_pelajaran";
$result = $pdo->query($query);
if (!$result) {
    die("Query Error: " . $pdo->errorInfo()[2]);
}

// Debugging: Jika tidak ada data
if ($result->rowCount() == 0) {
    echo "Tidak ada mata pelajaran yang ditemukan.";
    exit();
}

// Variabel untuk menyimpan pesan kesalahan
$error_message = "";

// Proses pilihan mata pelajaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pilihan = $_POST['pilihan'] ?? []; // Pilihan dari form (array checkbox)
    $pilihan = array_map('intval', $pilihan); // Pastikan ID berupa integer

    // Validasi untuk memastikan pelajar tidak memilih mata pelajaran yang sudah ada
    foreach ($pilihan as $id_pelajaran) {
        // Periksa apakah sudah ada data di tabel pelajar_pelajaran
        $stmt = $pdo->prepare("SELECT * FROM pelajar_pelajaran WHERE id_pelajar = :id_pelajar AND id_pelajaran = :id_pelajaran");
        $stmt->execute([
            'id_pelajar' => $id_pelajar,
            'id_pelajaran' => $id_pelajaran
        ]);
        
        if ($stmt->rowCount() > 0) {
            // Jika sudah ada, tampilkan pesan error
            $error_message = "Anda sudah memilih mata pelajaran ini sebelumnya: " . htmlspecialchars($id_pelajaran);
            break;  // Keluar dari loop jika sudah ditemukan
        }
    }

    // Jika tidak ada error, lanjutkan untuk menyimpan data
    if (empty($error_message)) {
        // Simpan pilihan mata pelajaran ke database
        foreach ($pilihan as $id_pelajaran) {
            // Ambil nama_pelajaran dari tabel mata_pelajaran berdasarkan id_pelajaran
            $stmt_nama_pelajaran = $pdo->prepare("SELECT nama_pelajaran FROM mata_pelajaran WHERE id_pelajaran = :id_pelajaran");
            $stmt_nama_pelajaran->execute(['id_pelajaran' => $id_pelajaran]);
            $nama_pelajaran = $stmt_nama_pelajaran->fetchColumn();

            // Jika belum ada, tambahkan ke tabel pelajar_pelajaran
            $stmt_insert = $pdo->prepare("INSERT INTO pelajar_pelajaran (id_pelajar, id_pelajaran, nama_pelajaran) VALUES (:id_pelajar, :id_pelajaran, :nama_pelajaran)");
            $stmt_insert->execute([
                'id_pelajar' => $id_pelajar,
                'id_pelajaran' => $id_pelajaran,
                'nama_pelajaran' => $nama_pelajaran  // Simpan nama_pelajaran di sini
            ]);
        }

        // Redirect ke dashboard setelah menyimpan pilihan
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Mata Pelajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Pilih Mata Pelajaran</h2>
    
    <!-- Jika ada error, tampilkan pesan kesalahan -->
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label for="id_pelajaran" class="form-label">Pilih Mata Pelajaran:</label>
            <div>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pilihan[]" value="<?= htmlspecialchars($row['id_pelajaran']); ?>">
                        <label class="form-check-label">
                            <?= htmlspecialchars($row['nama_pelajaran']); ?> 
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pilihan</button>
    </form>
</div>
</body>
</html>
