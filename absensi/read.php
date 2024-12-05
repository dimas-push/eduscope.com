<?php
require_once('../db/db.php'); // File koneksi database

// Query untuk membaca data absensi
$sql = "SELECT a.id_absensi, a.tanggal, a.status, r.name 
        FROM absensi a 
        JOIN register r ON a.idname = r.idname";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID Absensi: " . $row['id_absensi'] . "<br>";
        echo "Tanggal: " . $row['tanggal'] . "<br>";
        echo "Status: " . $row['status'] . "<br>";
        echo "Nama User: " . $row['name'] . "<br><hr>";
    }
} else {
    echo "Tidak ada data absensi.";
}
?>