<?php
require_once('../db/database.php');

// Filter mata pelajaran
$id_pelajaran_terpilih = $_GET['id_pelajaran'] ?? null;

// Ambil daftar materi berdasarkan mata pelajaran
$materi_result = null;
if ($id_pelajaran_terpilih) {
    $stmt = $conn->prepare("SELECT id_materi, judul_materi FROM materi_pelajaran WHERE id_pelajaran = ?");
    $stmt->bind_param("i", $id_pelajaran_terpilih);
    $stmt->execute();
    $materi_result = $stmt->get_result();
    $stmt->close();
}

// Ambil data materi yang dipilih untuk diedit
$id_materi = $_GET['id_materi'] ?? null;
$materi_data = null;
if ($id_materi) {
    $stmt = $conn->prepare("SELECT * FROM materi_pelajaran WHERE id_materi = ?");
    $stmt->bind_param("i", $id_materi);
    $stmt->execute();
    $materi_result_edit = $stmt->get_result();
    $materi_data = $materi_result_edit->fetch_assoc();
    $stmt->close();
}

// Proses update materi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materi = $_POST['id_materi'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $jenis_materi = $_POST['jenis_materi'];

    // Pastikan ada data materi sebelum mengakses file_path
    $file_path = isset($materi_data['file_path']) ? $materi_data['file_path'] : null; // Jika tidak ada, set null

    // Jika file baru diunggah
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/materi/";
        $file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['file']["name"]));
        $target_file = $target_dir . $file_name;

        // Validasi tipe file
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 'video/mp4'];
        if (!in_array($_FILES['file']["type"], $allowed_types)) {
            die("Tipe file tidak diizinkan. Hanya PDF, JPEG, PNG, atau MP4 yang diperbolehkan.");
        }

        if (move_uploaded_file($_FILES['file']["tmp_name"], $target_file)) {
            $file_path = "uploads/materi/" . $file_name;
        } else {
            die("Gagal mengupload file.");
        }
    }

    // Update data di database
    $stmt = $conn->prepare("UPDATE materi_pelajaran SET judul_materi = ?, deskripsi = ?, jenis_materi = ?, file_path = ? WHERE id_materi = ?");
    $stmt->bind_param("ssssi", $judul, $deskripsi, $jenis_materi, $file_path, $id_materi);

    if ($stmt->execute()) {
        echo "Materi berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui materi: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Materi</title>
</head>
<body>
    <h1>Update Materi</h1>

    <!-- Pilih Materi -->
    <form method="GET" action="update1.php">
        <input type="hidden" name="id_pelajaran" value="<?php echo htmlspecialchars($id_pelajaran_terpilih); ?>">
        <label for="id_materi">Pilih Materi:</label>
        <select name="id_materi" id="id_materi" required>
            <option value="">-- Pilih Materi --</option>
            <?php while ($row = $materi_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id_materi']); ?>" 
                    <?php echo $id_materi == $row['id_materi'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['judul_materi']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Edit</button>
    </form>

    <!-- Form Update Materi -->
    <?php if ($materi_data): ?>
        <form method="POST" enctype="multipart/form-data" action="update1.php">
            <input type="hidden" name="id_materi" value="<?php echo htmlspecialchars($materi_data['id_materi']); ?>">

            <label for="judul">Judul Materi:</label>
            <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($materi_data['judul_materi']); ?>" required><br><br>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" required><?php echo htmlspecialchars($materi_data['deskripsi']); ?></textarea><br><br>

            <label for="jenis_materi">Jenis Materi:</label>
            <select name="jenis_materi" id="jenis_materi" required>
                <option value="PDF" <?php echo $materi_data['jenis_materi'] == 'PDF' ? 'selected' : ''; ?>>PDF</option>
                <option value="Image" <?php echo $materi_data['jenis_materi'] == 'Image' ? 'selected' : ''; ?>>Image</option>
                <option value="Video" <?php echo $materi_data['jenis_materi'] == 'Video' ? 'selected' : ''; ?>>Video</option>
            </select><br><br>

            <label for="file">Upload File Baru (Opsional):</label>
            <input type="file" name="file" id="file"><br><br>

            <button type="submit">Update Materi</button>
        </form>
    <?php endif; ?>
</body>
</html>
