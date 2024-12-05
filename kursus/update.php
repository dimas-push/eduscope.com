<?php
require_once('../db/db.php');

// Ambil ID kursus dari query string
$idkursus = $_GET['id'] ?? '';

if (empty($idkursus) || !is_numeric($idkursus)) {
    die("ID kursus tidak valid.");
}

// Ambil detail kursus
$sql = "SELECT * FROM kursus WHERE idkursus = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idkursus);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Kursus tidak ditemukan.");
}

$kursus = $result->fetch_assoc();

// Proses update kursus
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi data
    $name = htmlspecialchars($_POST['name']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    // Pastikan tanggal valid
    if (!strtotime($tanggal_mulai) || !strtotime($tanggal_selesai)) {
        die("Tanggal tidak valid.");
    }

    // Update kursus menggunakan prepared statement
    $sql = "UPDATE kursus SET 
            name = ?, 
            deskripsi = ?, 
            kategori = ?, 
            tanggal_mulai = ?, 
            tanggal_selesai = ? 
            WHERE idkursus = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $name, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $idkursus);

    if ($stmt->execute()) {
        echo "Kursus berhasil diperbarui!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Kursus</title>
</head>

<body>
    <h2>Update Kursus</h2>
    <form method="POST" action="">
        <label for="name">Nama Kursus:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($kursus['name']) ?>" required><br><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi"
            required><?= htmlspecialchars($kursus['deskripsi']) ?></textarea><br><br>

        <label for="kategori">Kategori:</label>
        <select id="kategori" name="kategori" required>
            <option value="Bahasa" <?= $kursus['kategori'] == 'Bahasa' ? 'selected' : '' ?>>Bahasa</option>
            <option value="IPA" <?= $kursus['kategori'] == 'IPA' ? 'selected' : '' ?>>IPA</option>
            <option value="IPS" <?= $kursus['kategori'] == 'IPS' ? 'selected' : '' ?>>IPS</option>
            <option value="Matematika" <?= $kursus['kategori'] == 'Matematika' ? 'selected' : '' ?>>Matematika</option>
        </select><br><br>

        <label for="tanggal_mulai">Tanggal Mulai:</label>
        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= $kursus['tanggal_mulai'] ?>"
            required><br><br>

        <label for="tanggal_selesai">Tanggal Selesai:</label>
        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= $kursus['tanggal_selesai'] ?>"
            required><br><br>

        <button type="submit">Update</button>
    </form>
</body>

</html>