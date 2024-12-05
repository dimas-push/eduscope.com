<?php
require '../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $kapasitas_max = $_POST['kapasitas_max'];

    $query = "INSERT INTO jadwal_reservasi (mata_pelajaran, tanggal, jam, kapasitas_max) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $mata_pelajaran, $tanggal, $jam, $kapasitas_max);

    if ($stmt->execute()) {
        echo "Jadwal berhasil ditambahkan.";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Tambah Jadwal</title>
</head>

<body>
    <h1>Tambah Jadwal Reservasi</h1>
    <form method="POST">
        <label for="mata_pelajaran">Mata Pelajaran:</label>
        <select name="mata_pelajaran" id="mata_pelajaran" required>
            <?php
            $query = "SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_pelajaran']}'>{$row['nama_pelajaran']}</option>";
            }
            ?>
        </select>

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" required>

        <label for="jam">Jam:</label>
        <input type="time" name="jam" id="jam" required>

        <label for="kapasitas_max">Kapasitas Maksimum:</label>
        <input type="number" name="kapasitas_max" id="kapasitas_max" value="5" required>

        <button type="submit">Tambah Jadwal</button>
    </form>
</body>

</html>