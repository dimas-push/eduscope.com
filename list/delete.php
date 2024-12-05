<?php
require '../db/database.php';

$query = "SELECT j.id_jadwal, m.nama_pelajaran, j.tanggal, j.jam, j.kapasitas_max,
                 COUNT(r.id_reservasi) AS jumlah_terisi
          FROM jadwal_reservasi j
          JOIN mata_pelajaran m ON j.id_mata_pelajaran = m.id_pelajaran
          LEFT JOIN reservasi r ON j.id_jadwal = r.id_jadwal
          GROUP BY j.id_jadwal";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo "{$row['nama_pelajaran']} - {$row['tanggal']} {$row['jam']} 
          (Terisi: {$row['jumlah_terisi']} / {$row['kapasitas_max']})";
}
?>