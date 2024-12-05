<?php
require '../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $id_pelajar = $_SESSION['id_pelajar'];
    $id_jadwal = $_POST['id_jadwal'];

    $query = "SELECT (kapasitas_max - COUNT(r.id_reservasi)) AS slot_tersedia
              FROM jadwal_reservasi j
              LEFT JOIN reservasi r ON j.id_jadwal = r.id_jadwal AND r.status = 'diterima'
              WHERE j.id_jadwal = ?
              GROUP BY j.id_jadwal";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_jadwal);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['slot_tersedia'] <= 0) {
        echo "Kapasitas penuh untuk jadwal ini.";
        exit;
    }

    $query_insert = "INSERT INTO reservasi (id_pelajar, id_jadwal, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($query_insert);
    $stmt->bind_param("ii", $id_pelajar, $id_jadwal);

    if ($stmt->execute()) {
        echo "Reservasi berhasil, menunggu konfirmasi admin.";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
}
?>

<form method="POST" action="reservasi.php">
    <label for="mata_pelajaran">Mata Pelajaran:</label>
    <select name="id_jadwal" id="mata_pelajaran" required>
        <?php
        require '../db/database.php';

        $query = "SELECT j.id_jadwal, m.nama_pelajaran, j.tanggal, j.jam, 
                         (j.kapasitas_max - COUNT(r.id_reservasi)) AS slot_tersedia
                  FROM jadwal_reservasi j
                  JOIN mata_pelajaran m ON j.id_mata_pelajaran = m.id_pelajaran
                  LEFT JOIN reservasi r ON j.id_jadwal = r.id_jadwal AND r.status = 'diterima'
                  GROUP BY j.id_jadwal
                  HAVING slot_tersedia > 0";

        $result = $conn->query($query);

        if (!$result) {
            die("Query gagal: " . $conn->error); // Debug query jika gagal
        }

        if ($result->num_rows === 0) {
            echo "<option>Tidak ada jadwal tersedia</option>";
        } else {
            while ($row = $result->fetch_assoc()) {
                $slot_tersedia = $row['slot_tersedia'];
                echo "<option value='{$row['id_jadwal']}'>
                        {$row['nama_pelajaran']} - {$row['tanggal']} {$row['jam']} (Slot tersedia: $slot_tersedia)
                      </option>";
            }
        }
        ?>
    </select>

    <button type="submit">Reservasi</button>
</form>