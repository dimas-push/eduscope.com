<?php
require_once('../db/database.php');

// Ambil daftar mata pelajaran untuk dropdown
$pelajaran_result = $conn->query("SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran");
if (!$pelajaran_result) {
    die("Gagal mengambil daftar mata pelajaran: " . $conn->error);
}

// Variabel untuk filter
$id_pelajaran_terpilih = $_GET['id_pelajaran'] ?? null;

// Query untuk mengambil materi berdasarkan mata pelajaran yang dipilih
$materi_query = "
    SELECT 
        mp.id_materi, 
        mpl.nama_pelajaran, 
        mp.jenis_materi, 
        mp.judul_materi, 
        mp.file_path, 
        mp.deskripsi, 
        mp.created_at, 
        mp.updated_at 
    FROM 
        materi_pelajaran AS mp
    JOIN 
        mata_pelajaran AS mpl 
    ON 
        mp.id_pelajaran = mpl.id_pelajaran
";

if ($id_pelajaran_terpilih) {
    $materi_query .= " WHERE mp.id_pelajaran = ?";
    $stmt = $conn->prepare($materi_query);
    $stmt->bind_param("i", $id_pelajaran_terpilih);
    $stmt->execute();
    $materi_result = $stmt->get_result();
    $stmt->close();
} else {
    $materi_query .= " LIMIT 0"; // Jangan tampilkan materi jika belum memilih pelajaran
    $materi_result = $conn->query($materi_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Materi Pelajaran</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Daftar Materi Pelajaran</h1>

    <!-- Form Pilih Mata Pelajaran -->
    <form method="GET" action="read.php">
        <label for="id_pelajaran">Pilih Mata Pelajaran:</label>
        <select name="id_pelajaran" id="id_pelajaran" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php while ($row = $pelajaran_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id_pelajaran']); ?>" 
                    <?php echo $id_pelajaran_terpilih == $row['id_pelajaran'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['nama_pelajaran']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Tampilkan</button>
    </form>

    <!-- Tabel untuk menampilkan data -->
    <table>
        <thead>
            <tr>
                <th>ID Materi</th>
                <th>Nama Pelajaran</th>
                <th>Jenis Materi</th>
                <th>Judul Materi</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Dibuat</th>
                <th>Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($materi_result && $materi_result->num_rows > 0): ?>
                <?php while ($row = $materi_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_materi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pelajaran']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_materi']); ?></td>
                        <td><?php echo htmlspecialchars($row['judul_materi']); ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])): ?>
                                <a href="../<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">Lihat File</a>
                            <?php else: ?>
                                Tidak Ada File
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Tidak ada materi untuk mata pelajaran yang dipilih.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
