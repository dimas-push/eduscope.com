<?php
require_once('../db/database.php');

$sql = "SELECT * FROM kursus";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['idkursus'] . "<br>";
        echo "Nama: " . $row['name'] . "<br>";
        echo "Deskripsi: " . $row['deskripsi'] . "<br>";
        echo "Kategori: " . $row['kategori'] . "<br>";
        echo "Tanggal Mulai: " . $row['tanggal_mulai'] . "<br>";
        echo "Tanggal Selesai: " . $row['tanggal_selesai'] . "<hr>";
    }
} else {
    echo "Tidak ada kursus.";
}
?>