<?php
session_start();
// Pastikan include koneksi
include('db/database.php');

// Cek apakah data form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $spesialisasi = $_POST['spesialisasi'];

    // Validasi password
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data awal ke database tanpa foto
    $query = $conn->prepare("INSERT INTO tutor (nama, email, no_hp, password, spesialisasi) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $nama, $email, $no_hp, $hashed_password, $spesialisasi);

    if ($query->execute()) {
        // Dapatkan ID tutor yang baru saja dimasukkan
        $id_tutor = $conn->insert_id;

        // Validasi upload foto
        $foto_profil_dest = 'uploads/user_placeholder.png'; // Default foto
        if ($_FILES['foto_profil']['error'] === 0) {
            $foto_profil_tmp_name = $_FILES['foto_profil']['tmp_name'];
            $foto_extension = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
            $foto_profil_dest = 'uploads/fp_tutor/' . $id_tutor . '.' . $foto_extension;

            if (!move_uploaded_file($foto_profil_tmp_name, $foto_profil_dest)) {
                die("Failed to upload file!");
            }

            // Update nama foto di database
            $update_query = $conn->prepare("UPDATE tutor SET foto_profil = ? WHERE id_tutor = ?");
            $update_query->bind_param("si", $foto_profil_dest, $id_tutor);
            if (!$update_query->execute()) {
                die("Failed to update profile picture!");
            }
        }

        // Redirect ke halaman login setelah selesai
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2 class="text-center">Register as Tutor</h2>
        <form action="register-tutor.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="mb-3">
                <label for="foto_profil" class="form-label">Profile Photo</label>
                <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="spesialisasi" class="form-label">Specialization</label>
                <textarea class="form-control" id="spesialisasi" name="spesialisasi" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!-- Script to toggle password visibility -->
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var confirmPasswordField = document.getElementById("confirm_password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
            }
        }
    </script>
</body>
</html>
