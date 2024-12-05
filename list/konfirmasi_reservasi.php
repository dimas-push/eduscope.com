<?php
require '../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reservasi = $_POST['id_reservasi'];
    $status = $_POST['status'];

    $query = "UPDATE reservasi SET status = ? WHERE id_reservasi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id_reservasi);

    if ($stmt->execute()) {
        echo "<script>
            alert('Status reservasi diperbarui.');
            window.location.href = 'admin_reservasi.php';
        </script>";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
}
?>