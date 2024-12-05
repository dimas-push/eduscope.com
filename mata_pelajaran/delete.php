<?php
require_once('../db/database.php'); // Koneksi ke database
session_start();

// Proses penghapusan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_pelajaran'])) {
    $id_pelajaran = $_POST['id_pelajaran'];

    // Validasi apakah ID Pelajaran ada
    if (!$id_pelajaran) {
        die("ID pelajaran tidak valid.");
    }

    // Siapkan query untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM mata_pelajaran WHERE id_pelajaran = ?");
    if (!$stmt) {
        die("Kesalahan dalam mempersiapkan query: " . $conn->error);
    }

    // Bind parameter dan eksekusi query
    $stmt->bind_param("i", $id_pelajaran);
    if ($stmt->execute()) {
        echo "<p>Mata pelajaran berhasil dihapus.</p>";
        echo '<a href="read.php">Kembali ke Daftar Mata Pelajaran</a>';
        exit;
    } else {
        echo "<p>Kesalahan dalam eksekusi query: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Ambil data mata pelajaran untuk ditampilkan dalam form
$result = $conn->query("SELECT * FROM mata_pelajaran");
if (!$result) {
    die("Kesalahan query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Mata Pelajaran</title>
</head>
<body>
    <h1>Hapus Mata Pelajaran</h1>
    <form method="POST" action="delete.php">
        <label for="id_pelajaran">Pilih Mata Pelajaran:</label>
        <select id="id_pelajaran" name="id_pelajaran" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <option value="<?php echo htmlspecialchars($row['id_pelajaran']); ?>">
                    <?php echo htmlspecialchars($row['nama_pelajaran']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>
        <button type="submit" name="delete_pelajaran">Hapus Mata Pelajaran</button>
    </form>
    <br>
    <a href="read.php">Kembali ke Daftar Mata Pelajaran</a>
</body>
</html>
