<?php
require_once('../db/database.php');

// Ambil daftar mata pelajaran untuk dropdown
$pelajaran_result = $conn->query("SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran");
if (!$pelajaran_result) {
    die("Gagal mengambil daftar mata pelajaran: " . $conn->error);
}

// Filter mata pelajaran
$id_pelajaran_terpilih = $_GET['id_pelajaran'] ?? null;

// Ambil daftar materi berdasarkan mata pelajaran yang dipilih
$materi_result = null;
if ($id_pelajaran_terpilih) {
    $stmt = $conn->prepare("SELECT id_materi, judul_materi, file_path FROM materi_pelajaran WHERE id_pelajaran = ?");
    $stmt->bind_param("i", $id_pelajaran_terpilih);
    $stmt->execute();
    $materi_result = $stmt->get_result();
    $stmt->close();
}

// Proses hapus materi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_materi'])) {
    $id_materi = $_POST['id_materi'];

    // Ambil file path materi yang akan dihapus
    $stmt = $conn->prepare("SELECT file_path FROM materi_pelajaran WHERE id_materi = ?");
    $stmt->bind_param("i", $id_materi);
    $stmt->execute();
    $materi = $stmt->get_result()->fetch_assoc();
    $file_path = $materi['file_path'];
    $stmt->close();

    // Hapus file dari server jika ada
    if ($file_path && file_exists("../" . $file_path)) {
        unlink("../" . $file_path);
    }

    // Hapus materi dari database
    $stmt = $conn->prepare("DELETE FROM materi_pelajaran WHERE id_materi = ?");
    $stmt->bind_param("i", $id_materi);
    if ($stmt->execute()) {
        echo "Materi berhasil dihapus!";
    } else {
        echo "Gagal menghapus materi: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Materi</title>
</head>
<body>
    <h1>Hapus Materi</h1>

    <!-- Pilih Mata Pelajaran -->
    <form method="GET" action="delete.php">
        <label for="id_pelajaran">Pilih Mata Pelajaran:</label>
        <select name="id_pelajaran" id="id_pelajaran" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php while ($row = $pelajaran_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id_pelajaran']); ?>" 
                    <?php echo $id_pelajaran_terpilih == $row['id_pelajaran'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['nama_pelajaran']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Tampilkan Materi</button>
    </form>

    <!-- Pilih Materi untuk Dihapus -->
    <?php if ($materi_result && $materi_result->num_rows > 0): ?>
        <form method="POST" action="delete.php">
            <input type="hidden" name="id_pelajaran" value="<?php echo htmlspecialchars($id_pelajaran_terpilih); ?>">
            <label for="id_materi">Pilih Materi untuk Dihapus:</label>
            <select name="id_materi" id="id_materi" required>
                <option value="">-- Pilih Materi --</option>
                <?php while ($row = $materi_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id_materi']); ?>">
                        <?php echo htmlspecialchars($row['judul_materi']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Hapus Materi</button>
        </form>
    <?php endif; ?>

</body>
</html>
