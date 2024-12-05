<?php
require_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM kursus WHERE idkursus = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Kursus berhasil dihapus!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Hapus Kursus</h2>
<form method="POST" action="delete.php">
    <label for="id">ID Kursus:</label>
    <input type="number" id="id" name="id" required>
    <button type="submit">Hapus</button>
</form>