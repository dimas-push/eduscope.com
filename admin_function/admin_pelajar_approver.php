<?php
session_start();
include('../db/database.php'); // Koneksi ke database

// Cek apakah user sudah login dan perannya adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil data pelajar, nama pelajar, dan status berdasarkan ID unik di tabel pelajar_pelajaran
$stmt = $pdo->prepare("
    SELECT pp.id, pp.id_pelajar, up.nama, pp.status, pp.nama_pelajaran
    FROM pelajar_pelajaran pp
    JOIN user_pelajar up ON pp.id_pelajar = up.id_pelajar
    ORDER BY pp.id
");
$stmt->execute();
$pelajar_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses update status berdasarkan ID unik dari tabel pelajar_pelajaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    // Ambil ID pelajar yang dipilih dan status yang ingin diubah
    $status_updates = $_POST['status']; // Status yang dipilih untuk masing-masing pelajar

    foreach ($status_updates as $id => $status) {
        // Update status berdasarkan ID pelajar dan status yang dipilih
        $update_stmt = $pdo->prepare("
            UPDATE pelajar_pelajaran 
            SET status = :status 
            WHERE id = :id
        ");
        $update_stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    // Redirect untuk mencegah pengulangan form submission
    header("Location: admin_pelajar_approver.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Pelajar</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Tambahkan path CSS -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Daftar Pelajar</h1>
        
        <!-- Form untuk memilih beberapa pelajar -->
        <form action="admin_pelajar_approver.php" method="POST">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <!---<th>ID Pelajar</th>--->
                        <th>Nama Pelajar</th>
                        <th>Nama Pelajaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pelajar_list as $pelajar): ?>
                        <tr>
                            <!---<td><?= htmlspecialchars($pelajar['id_pelajar']); ?></td>--->
                            <td><?= htmlspecialchars($pelajar['nama']); ?></td>
                            <td><?= htmlspecialchars($pelajar['nama_pelajaran']); ?></td>
                            <td>
                                <!-- Dropdown untuk memilih status -->
                                <select name="status[<?= $pelajar['id']; ?>]" class="form-control form-control-sm" style="width: 120px;">
                                    <option value="pending" <?= $pelajar['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="active" <?= $pelajar['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Tombol Apply untuk mengupdate status -->
            <button type="submit" name="update_status" class="btn btn-primary">
                Apply Status ke yang dipilih
            </button>
        </form>
    </div>
</body>
</html>
