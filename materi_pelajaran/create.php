<?php
require_once('../db/database.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelajaran = $_POST['id_pelajaran'] ?? null; // ID Pelajaran dari dropdown
    $judul = $_POST['judul'] ?? ''; // Judul Materi
    $deskripsi = $_POST['deskripsi'] ?? ''; // Deskripsi Materi
    $file = $_FILES['file'] ?? null; // File yang diupload

    // Validasi input
    if (!$id_pelajaran) {
        die("Mata pelajaran belum dipilih. Silakan pilih dari dropdown.");
    }

    // Path direktori untuk menyimpan file
    $target_dir = "../uploads/materi/";
    $file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($file["name"]));
    $target_file = $target_dir . $file_name;

    // Membuat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Validasi tipe file
    $allowed_types = [
        'application/pdf',        // PDF
        'image/jpeg',
        'image/png', // Gambar
        'video/mp4',
        'video/mkv',
        'video/avi' // Video
    ];
    if (!in_array($file["type"], $allowed_types)) {
        die("Tipe file tidak diizinkan. Hanya PDF, Gambar (JPEG, PNG), atau Video (MP4, MKV, AVI) yang diperbolehkan.");
    }

    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Path file untuk disimpan di database
        $file_url = "uploads/materi/" . $file_name;

        // Tentukan jenis materi berdasarkan tipe file
        switch ($file["type"]) {
            case "application/pdf":
                $tipe_file = "pdf";
                break;
            case "video/mp4":
                $tipe_file = "video";
                break;
            case "image/jpeg":
            case "image/png":
                $tipe_file = "foto";
                break;
            case "application/vnd.ms-powerpoint":
            case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
                $tipe_file = "ppt";
                break;
            default:
                die("Jenis file tidak didukung."); // Jika file tidak cocok dengan ENUM
        }

        // Query untuk memasukkan data ke tabel materi_pelajaran
        $stmt = $conn->prepare("
            INSERT INTO materi_pelajaran 
            (id_pelajaran, jenis_materi, judul_materi, file_path, deskripsi, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        if (!$stmt) {
            die("Query gagal disiapkan: " . $conn->error);
        }

        $stmt->bind_param("issss", $id_pelajaran, $tipe_file, $judul, $file_url, $deskripsi);

        if ($stmt->execute()) {
            echo "Materi berhasil ditambahkan!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "File gagal diupload.";
    }

}

// Ambil daftar mata pelajaran untuk dropdown
$result = $conn->query("SELECT id_pelajaran, nama_pelajaran FROM mata_pelajaran");
if (!$result) {
    die("Gagal mengambil daftar mata pelajaran: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi</title>
</head>

<body>
    <h1>Tambah Materi</h1>
    <form method="POST" enctype="multipart/form-data" action="create.php">
        <!-- Dropdown Nama Mata Pelajaran -->
        <label for="id_pelajaran">Pilih Mata Pelajaran:</label>
        <select name="id_pelajaran" id="id_pelajaran" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id_pelajaran']); ?>">
                    <?php echo htmlspecialchars($row['nama_pelajaran']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <!-- Input Judul Materi -->
        <label for="judul">Judul Materi:</label>
        <input type="text" name="judul" id="judul" placeholder="Judul Materi" required><br><br>

        <!-- Input Deskripsi -->
        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi Materi"></textarea><br><br>

        <!-- Upload File -->
        <label for="file">Upload File Materi:</label>
        <input type="file" name="file" id="file" required>
        <p>* Tipe file yang diperbolehkan: PDF, Gambar (JPEG, PNG), atau Video (MP4, MKV, AVI)</p><br><br>

        <!-- Tombol Submit -->
        <button type="submit">Tambah Materi</button>
    </form>
</body>

</html>