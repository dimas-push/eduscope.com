<?php
require 'db.php'; // pastikan db.php sudah berisi koneksi ke database
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = trim($_POST['email']);
    $name = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($name)) {
        $errors['nama'] = "Name is required.";
    }

    if (empty($no_hp) || !preg_match('/^[0-9+\-\s]+$/', $no_hp)) {
        $errors['no_hp'] = "Nomor HP tidak valid.";
    }

    if (strlen($password) < 6) {
        $errors['password'] = "Password harus lebih dari 6 karakter.";
    }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords tidak sama.";
    }

    // Jika tidak ada error
    if (count($errors) === 0) {
        // Koneksi ke database
        $conn = mysqli_connect('localhost', 'root', '', 'eduscope');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Cek jika email sudah terdaftar
        $checkQuery = "SELECT * FROM user_pelajar WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['email'] = "Email sudah digunakan.";
        } else {
            // Masukkan data ke database
            $insertQuery = "INSERT INTO user_pelajar (email, nama, no_hp, password, created_at) 
                            VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $email, $name, $no_hp, $hashedPassword);

            if ($stmt->execute()) {
                $successMessage = "Pendaftaran berhasil!";
            } else {
                $errors['db'] = "Error: " . $stmt->error;
            }
        }

        // Tutup koneksi
        $stmt->close();
        $conn->close();
    }
}

// Tampilkan pesan error atau sukses
if (!empty($errors)) {
    foreach ($errors as $key => $error) {
        echo "<p style='color:red;'>$key: $error</p>";
    }
}
if (!empty($successMessage)) {
    echo "<p style='color:green;'>$successMessage</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        function errorRegister() {
            Swal.fire({
                position: 'top-center',
                icon: 'error',
                html: document.getElementById("msg").value,
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                window.location.href = 'register.php';
            })
        }
    </script>

</head>

<body>

    <div class="container">
        <img src="layout/img/logo0.png" alt="Logo" class="logo">

        <?php if ($successMessage): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form class="register-form" method="POST">
            <div class="form-group">
                <input type="text" name="nama" placeholder="Nama" required
                    value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES); ?>">
                <?php if (isset($errors['nama'])): ?>
                    <div class="error"><?php echo $errors['nama']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="text" name="no_hp" autocomplete="off" placeholder="Nomor HP" required>
                <?php if (isset($errors['no_hp'])): ?>
                    <div class="error"><?php echo $errors['no_hp']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required
                    value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required id="password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye" id="eye-icon-password-1"></i>
                </button>
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required
                    id="confirm-password">
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)">
                    <i class="bi bi-eye" id="eye-icon-confirm-password"></i>
                </button>
                <?php if (isset($errors['confirmPassword'])): ?>
                    <div class="error"><?php echo $errors['confirmPassword']; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="submit-btn">Register</button>
        </form>
        <div class="footer">
            <p>Sudah punya akun? <a href="login.php" style="color: #3A89FF;">Masuk di sini</a></p>
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
    </script>
    <?php if (!empty($successMessage)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil',
                text: '<?php echo $successMessage; ?>',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php'; // Mengarahkan ke halaman login
                }
            });
        </script>
    <?php elseif (!empty($errorMessage)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                text: '<?php echo $errorMessage; ?>',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
    <?php endif; ?>





</body>

</html>