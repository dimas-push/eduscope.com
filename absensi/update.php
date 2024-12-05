<?php
require_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_absensi = $_POST['id_absensi'];
    $status_kehadiran = $_POST['status_kehadiran'];

    $sql = "UPDATE absensi SET status_kehadiran = '$status_kehadiran' WHERE id_absensi = $id_absensi";

    if ($conn->query($sql) === TRUE) {
        echo "Absensi berhasil diperbarui!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" action="update.php">
    <input type="number" name="id_absensi" placeholder="ID Absensi" required>
    <select name="status_kehadiran" required>
        <option value="Hadir">Hadir</option>
        <option value="Tidak Hadir">Tidak Hadir</option>
    </select>
    <button type="submit">Perbarui Absensi</button>
</form>