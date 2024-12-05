<?php
session_start();
require 'db.php';

$login_failed = false; // Variabel untuk menentukan login gagal

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = $_POST['login_input'] ?? ''; // Login input: nama, email, no_hp
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($login_input) || empty($password)) {
        $login_failed = true;
    } else {
        // Query database untuk validasi login
        $stmt = $pdo->prepare("
            SELECT * FROM user_pelajar 
            WHERE nama = :input OR email = :input OR no_hp = :input
        ");
        $stmt->execute(['input' => $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Simpan data ke sesi
            $_SESSION['id_pelajar'] = $user['id_pelajar'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['foto_profil'] = $user['foto_profil']; // Simpan foto ke sesi

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $login_failed = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert2 -->
</head>

<body>
    <div class="container">
        <img src="layout/img/logo0.png" alt="Logo" class="logo">
        <form class="login-form" method="POST">
            <div class="form-group">
                <input type="text" id="login_input" name="login_input" placeholder="Email, Nomor Telepon ataupun Nama"
                    required value="<?php echo htmlspecialchars($login_input ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required id="password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye" id="eye-icon-password"></i>
                </button>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
        <div class="footer">
            <p>Belum punya akun? <a href="register.php" style="color: #3A89FF;">Daftar di sini</a></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, icon) {
            const passwordInput = document.getElementById(inputId);
            const passwordType = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", passwordType);

            // Toggle between eye and eye slash icon
            icon.innerHTML = passwordType === "text" ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        }

        // Tampilkan SweetAlert jika login gagal
        <?php if ($login_failed): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Invalid login input or password.',
                confirmButtonText: 'Coba Lagi'
            });
        <?php endif; ?>
    </script>
</body>

</html>