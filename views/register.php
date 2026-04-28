<?php
require_once '../config/database.php';
require_once '../classes/auth.php';

$db = new Database();
$auth = new Auth($db->getConnection());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->register($username, $email, $password)) {
        header("Location: login.php");
        exit;
    } else {
        $message = "Registrasi gagal, mungkin email atau username sudah terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Pesbuk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e9f0ff, #f8fafc);
}

/* CARD */
.register-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 2.5rem;
    width: 100%;
    max-width: 420px;
}

/* INPUT */
.form-control {
    border-radius: 12px;
    padding: 12px 14px;
    border: 1.5px solid #e5e7eb;
    transition: 0.2s;
}
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
}

/* BUTTON */
.btn-primary {
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    transition: 0.2s;
}
.btn-primary:hover {
    transform: translateY(-2px);
}

/* SIDE */
.side-panel {
    background: linear-gradient(135deg, #0d6efd, #4f8cff);
    color: white;
}

/* ICON INPUT */
.input-group-text {
    background: #f1f5f9;
    border-radius: 12px 0 0 12px;
    border: 1.5px solid #e5e7eb;
}
</style>
</head>

<body>

<div class="container-fluid">
<div class=" min-vh-100">

    <!-- FORM -->

        <div class="register-card justify-content-center d-flex flex-column mx-auto my-5">

            <h3 class="fw-bold mb-1">Buat Akun</h3>
            <p class="text-muted mb-4">Gabung ke Pesbuk sekarang 🚀</p>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label small text-muted">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label small text-muted">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="email@gmail.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label small text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus"></i> Daftar
                </button>

            </form>

            <p class="text-center mt-4 small text-muted">
                Sudah punya akun? 
                <a href="login.php" class="fw-semibold text-decoration-none">Login</a>
            </p>

        </div>

    </div>
</div>
</div>

</body>
</html>