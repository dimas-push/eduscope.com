<?php
require '../db/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Reservasi</title>
    <link rel="stylesheet" href="admin-style.css">
</head>

<body>
    <h1>Daftar Reservasi</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT r.id_reservasi, u.nama AS nama_siswa, m.nama_pelajaran, r.tanggal, r.jam, r.status 
                      FROM reservasi r 
                      JOIN user_pelajar u ON r.id_pelajar = u.id_pelajar 
                      JOIN mata_pelajaran m ON r.id_mata_pelajaran = m.id_pelajaran";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($row['nama_pelajaran']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal']); ?></td>
                    <td><?= htmlspecialchars($row['jam']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form method="POST" action="konfirmasi_reservasi.php">
                            <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi']; ?>">
                            <select name="status">
                                <option value="diterima" <?= $row['status'] == 'diterima' ? 'selected' : ''; ?>>Diterima
                                </option>
                                <option value="ditolak" <?= $row['status'] == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>