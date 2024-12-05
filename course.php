<?php
session_start();
include('db/database.php'); // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['pelajar', 'tutor', 'admin'])) {
    header("Location: login.php");
    exit();
}

// Tangkap parameter `id_pelajaran` dari URL
if (isset($_GET['id_pelajaran'])) {
    $id_pelajaran = (int) $_GET['id_pelajaran'];

    // Cek apakah pengguna memiliki akses ke pelajaran ini
    if ($_SESSION['role'] === 'pelajar') {
        $stmtCheck = $pdo->prepare("
            SELECT status
            FROM pelajar_pelajaran
            WHERE id_pelajar = :id_pelajar AND id_pelajaran = :id_pelajaran
        ");
        $stmtCheck->execute([
            'id_pelajar' => $_SESSION['id_user'],
            'id_pelajaran' => $id_pelajaran
        ]);
        $status = $stmtCheck->fetchColumn();
        $has_access = $status && $status !== 'pending';
    } elseif ($_SESSION['role'] === 'tutor') {
        $stmtCheck = $pdo->prepare("
            SELECT status
            FROM tutor_pelajaran
            WHERE id_tutor = :id_tutor AND id_pelajaran = :id_pelajaran
        ");
        $stmtCheck->execute([
            'id_tutor' => $_SESSION['id_user'],
            'id_pelajaran' => $id_pelajaran
        ]);
        $status = $stmtCheck->fetchColumn();
        $has_access = $status && $status !== 'pending';
    } elseif ($_SESSION['role'] === 'admin') {
        $has_access = true; // Admin memiliki akses bebas
    } else {
        $has_access = false;
    }

    // Jika tidak memiliki akses atau status `pending`, arahkan ke dashboard
    if (!$has_access) {
        header("Location: dashboard.php");
        exit();
    }

    // Query informasi mata pelajaran (dengan no_hp)
$stmt = $pdo->prepare("SELECT nama_pelajaran FROM mata_pelajaran WHERE id_pelajaran = :id_pelajaran");
$stmt->execute(['id_pelajaran' => $id_pelajaran]);
$pelajaran = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelajaran) {
    header("Location: dashboard.php");
    exit();
}


// Query mengambil data tutor
$stmtTutor = $pdo->prepare("
    SELECT t.nama, t.email, t.no_hp
    FROM tutor_pelajaran tp
    JOIN tutor t ON tp.id_tutor = t.id_tutor
    WHERE tp.id_pelajaran = :id_pelajaran
");
$stmtTutor->execute(['id_pelajaran' => $id_pelajaran]);
$tutors = $stmtTutor->fetchAll(PDO::FETCH_ASSOC);

    // Tangkap filter jenis materi jika ada
    $jenis_materi_filter = isset($_GET['jenis_materi']) ? $_GET['jenis_materi'] : null;

    // Query untuk mengambil materi terkait pelajaran
    $query = "
        SELECT id_materi, id_package_materi, jenis_materi, judul_materi, file_path, deskripsi, created_at
        FROM materi_pelajaran
        WHERE id_pelajaran = :id_pelajaran
    ";

    // Tambahkan filter jenis materi jika dipilih
    if ($jenis_materi_filter) {
        $query .= " AND jenis_materi = :jenis_materi";
    }

    // Mengelompokkan berdasarkan id_package_materi
    $query .= " ORDER BY id_package_materi, created_at";

    $stmtMateri = $pdo->prepare($query);

    $params = ['id_pelajaran' => $id_pelajaran];
    if ($jenis_materi_filter) {
        $params['jenis_materi'] = $jenis_materi_filter;
    }

    $stmtMateri->execute($params);
    $materi = $stmtMateri->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Jika `id_pelajaran` tidak ada di URL, arahkan ke dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pelajaran['nama_pelajaran']); ?> - Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center"><?= htmlspecialchars($pelajaran['nama_pelajaran']); ?></h1>
        <hr>

        

        <!-- Informasi Tutor -->
        <div class="mt-4">
    <h3>Tutor yang Mengampu</h3>
    <?php if (!empty($tutors)): ?>
        <ul class="list-group">
            <?php foreach ($tutors as $tutor): ?>
                <?php 
                // Konversi no_hp dari 08 ke +62
                $formatted_no_hp = preg_replace('/^0/', '+62', $tutor['no_hp']); 
                ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($tutor['nama']); ?></strong><br>
                    <span>Email: <?= htmlspecialchars($tutor['email']); ?></span><br>
                    <span>No. HP: <?= htmlspecialchars($tutor['no_hp']); ?></span><br>
                    <a href="https://wa.me/<?= htmlspecialchars($formatted_no_hp); ?>" 
                       class="btn btn-success mt-2" 
                       target="_blank">
                        Hubungi Saya
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">Belum ada tutor yang terdaftar untuk mata pelajaran ini.</p>
    <?php endif; ?>
</div>

        <!-- Filter Jenis Materi -->
        <form method="GET" class="mb-4">
            <input type="hidden" name="id_pelajaran" value="<?= htmlspecialchars($id_pelajaran); ?>">
            <div class="row">
                <div class="col-md-6">
                    <select name="jenis_materi" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jenis Materi</option>
                        <option value="video" <?= $jenis_materi_filter === 'video' ? 'selected' : ''; ?>>Video</option>
                        <option value="pdf" <?= $jenis_materi_filter === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                        <option value="ppt" <?= $jenis_materi_filter === 'ppt' ? 'selected' : ''; ?>>PPT</option>
                        <option value="gambar" <?= $jenis_materi_filter === 'gambar' ? 'selected' : ''; ?>>Gambar</option>
                        <option value="lainnya" <?= $jenis_materi_filter === 'lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </div>
        </form>

        <!-- Daftar Materi -->
        <div class="row">
            <?php 
            // Mengelompokkan materi berdasarkan id_package_materi
            $groupedMateri = [];
            foreach ($materi as $item) {
                $groupedMateri[$item['id_package_materi']][] = $item;
            }
            ?>

            <?php $packageIndex = 1; // Penomoran grup ?>
            <?php foreach ($groupedMateri as $packageId => $packageMateri): ?>
                <div class="col-md-12 mb-4">
                    <h4><?= htmlspecialchars($packageMateri[0]['judul_materi']); ?> (<?= count($packageMateri); ?> Materi)</h4>
                    <div class="row">
                        <?php $itemIndex = 1; // Penomoran item ?>
                        <?php foreach ($packageMateri as $item): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= $itemIndex++; ?>. <?= htmlspecialchars($item['judul_materi']); ?>
                                        </h5>
                                        <p class="card-text"><?= htmlspecialchars($item['deskripsi']); ?></p>
                                        <small class="text-muted">Dibuat pada: <?= htmlspecialchars($item['created_at']); ?></small>
                                    </div>
                                    <div class="card-footer">
                                        <a href="<?= htmlspecialchars($item['file_path']); ?>" class="btn btn-primary" target="_blank">
                                            Lihat Materi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($materi)): ?>
                <p class="text-center w-100">Belum ada materi untuk pelajaran ini.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
