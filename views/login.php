<?php
require_once '../config/database.php';
require_once '../classes/auth.php';


$db = new Database();
$auth = new Auth($db->getConnection());
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($auth->login($email, $password)) {
        header("Location: timeline.php");
        exit();
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Pesbuk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #e9f0ff, #f8fafc);
    font-family: 'Segoe UI', sans-serif;
}

/* CARD */
.login-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2.5rem;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* INPUT */
.form-control {
    border-radius: 12px;
    padding: 12px;
    border: 1.5px solid #e5e7eb;
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
}
.btn-primary:hover {
    transform: translateY(-2px);
}

/* SIDE */
.side {
    background: linear-gradient(135deg, #0d6efd, #4f8cff);
    color: white;
}
</style>
</head>

<body>

<div class="container-fluid">
<div class="min-vh-100">

    <!-- FORM -->
    <div class="d-flex align-items-center justify-content-center my-5">

        <div class="login-card">

            <h3 class="fw-bold mb-1">Selamat Datang</h3>
            <p class="text-muted mb-4">Masuk ke akun Pesbuk kamu</p>

            <form method="POST">
                <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label small text-muted">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="email@gmail.com" required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label class="form-label small text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-3 small">
                    <div>
                        <input type="checkbox"> Ingat saya
                    </div>
                    <a href="#" class="text-decoration-none">Lupa password?</a>
                </div>

                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>

            </form>

            <p class="text-center mt-4 small text-muted">
                Belum punya akun?
                <a href="register.php" class="fw-semibold text-decoration-none">Daftar</a>
            </p>

        </div>

    

</div>
</div>

</body>
</html>