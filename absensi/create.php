<?php
require_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kursus = $_POST['id_kursus'];
    $id_user = $_POST['id_user'];
    $tanggal = $_POST['tanggal'];
    $status_kehadiran = $_POST['status_kehadiran'];

    $sql = "INSERT INTO absensi (id_kursus, id_user, tanggal, status_kehadiran)
            VALUES ('$id_kursus', '$id_user', '$tanggal', '$status_kehadiran')";

    if ($conn->query($sql) === TRUE) {
        echo "Absensi berhasil ditambahkan!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" action="create.php">
    <input type="number" name="id_kursus" placeholder="ID Kursus" required>
    <input type="number" name="id_user" placeholder="ID User" required>
    <input type="date" name="tanggal" required>
    <select name="status_kehadiran" required>
        <option value="Hadir">Hadir</option>
        <option value="Tidak Hadir">Tidak Hadir</option>
    </select>
    <button type="submit">Tambah Absensi</button>
</form>