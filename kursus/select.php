<?php
require_once('../db/db.php');

// Ambil daftar kursus
$sql = "SELECT * FROM kursus";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursus untuk Update</title>
</head>

<body>
    <h2>Pilih Kursus yang Ingin Diperbarui</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['idkursus'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= $row['tanggal_mulai'] ?></td>
                    <td><?= $row['tanggal_selesai'] ?></td>
                    <td>
                        <form method="GET" action="update.php">
                            <input type="hidden" name="id" value="<?= $row['idkursus'] ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Tidak ada kursus yang ditemukan.</p>
    <?php endif; ?>
</body>

</html>