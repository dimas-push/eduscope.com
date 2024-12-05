<?php
session_start();
require_once('../db/database.php'); // Koneksi ke database

// Cek apakah user sudah login dan perannya adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil daftar tutor mata pelajaran
$stmt = $pdo->prepare("
    SELECT tp.id, tp.id_tutor, tp.nama, tp.id_pelajaran, tp.nama_pelajaran, tp.status
    FROM tutor_pelajaran tp
    ORDER BY tp.id
");
$stmt->execute();
$tutor_pelajaran_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses update status untuk tutor yang dipilih
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_status'])) {
    // Ambil semua status yang dipilih untuk tiap tutor
    foreach ($_POST['status'] as $id => $status) {
        // Update status hanya untuk tutor yang statusnya berubah
        if ($status !== 'no_change') {
            $update_stmt = $pdo->prepare("
                UPDATE tutor_pelajaran
                SET status = :status
                WHERE id = :id
            ");
            $update_stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
        }
    }

    // Redirect untuk mencegah pengulangan form submission
    header("Location: admin_tutor_approver.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve Tutor Mata Pelajaran</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Tambahkan path CSS -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Approval Tutor Mata Pelajaran</h1>

        <!-- Form untuk memilih status dan apply -->
        <form action="admin_tutor_approver.php" method="POST">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <!--<th>ID Tutor</th>-->
                        <th>Nama Tutor</th>
                        <th>Nama Pelajaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tutor_pelajaran_list as $tutor_pelajaran): ?>
                        <tr>
                            <!--<td><?= htmlspecialchars($tutor_pelajaran['id_tutor']); ?></td>-->
                            <td><?= htmlspecialchars($tutor_pelajaran['nama']); ?></td>
                            <td><?= htmlspecialchars($tutor_pelajaran['nama_pelajaran']); ?></td>
                            <td>
                                <select name="status[<?= $tutor_pelajaran['id']; ?>]" class="form-control form-control-sm" style="width: 120px;">
                                    <option value="no_change">Tidak Diubah</option>
                                    <option value="pending" <?= $tutor_pelajaran['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="active" <?= $tutor_pelajaran['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" name="apply_status" class="btn btn-success btn-block">Apply Status</button>
        </form>
    </div>
</body>
</html>
