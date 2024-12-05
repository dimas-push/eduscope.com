<?php
require_once('../db/database.php');

// Ambil daftar mata pelajaran untuk dropdown
$pelajaran_result = $conn->query("SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran");
if (!$pelajaran_result) {
    die("Gagal mengambil daftar mata pelajaran: " . $conn->error);
}

$id_pelajaran_terpilih = $_GET['id_pelajaran'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Mata Pelajaran</title>
</head>
<body>
    <h1>Pilih Mata Pelajaran</h1>

    <form method="GET" action="update1.php">
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

</body>
</html>
