<?php
require_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_absensi = $_POST['id_absensi'];

    $sql = "DELETE FROM absensi WHERE id_absensi = $id_absensi";

    if ($conn->query($sql) === TRUE) {
        echo "Absensi berhasil dihapus!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" action="delete.php">
    <input type="number" name="id_absensi" placeholder="ID Absensi" required>
    <button type="submit">Hapus Absensi</button>
</form>