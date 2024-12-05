<?php
require_once('../db/database.php'); // Pastikan file koneksi sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    // Query untuk menambahkan data
    $sql = "INSERT INTO kursus (name, deskripsi, kategori, tanggal_mulai, tanggal_selesai) VALUES ('$name', '$deskripsi', '$kategori', '$tanggal_mulai', '$tanggal_selesai')";

    if ($conn->query($sql) === TRUE) {
        echo "Kursus berhasil ditambahkan!";
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
    <title>Document</title>
</head>

<body>
    <form method="POST" action="create.php">
        <input type="text" name="name" placeholder="Nama Kursus" required>
        <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
        <select id="Kategori" name="kategori" placeholder="Kategori" required>
            <option value="Bahasa">Bahasa</option>
            <option value="IPA">IPA</option>
            <option value="IPS">IPS</option>
            <option value="Matematika">Matematika</option>
        </select>
        <input type="date" name="tanggal_mulai" required>
        <input type="date" name="tanggal_selesai" required>
        <button type="submit">Tambah Kursus</button>
    </form>

</body>

</html>