<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesbuk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    font-family: 'Segoe UI', sans-serif;
}

.navbar {
    backdrop-filter: blur(12px);
    background: rgba(255,255,255,0.85) !important;
}

.hero-card {
    border-radius: 24px;
    background: linear-gradient(135deg, #0d6efd, #4f8cff);
    color: white;
    padding: 3rem;
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}

.hero-card::before {
    content: "";
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    top: -50px;
    right: -50px;
}

.hero-title {
    font-weight: 800;
    font-size: 2.5rem;
}

.hero-img {
    max-width: 500px;
    transition: 0.4s;
}
.hero-img:hover {
    transform: translateY(-10px) scale(1.05);
}

.btn-soft {
    border-radius: 12px;
    padding: 10px 18px;
    font-weight: 500;
}

.feature-card {
    border-radius: 16px;
    padding: 20px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}
.feature-card:hover {
    transform: translateY(-5px);
}

.icon-box {
    width: 55px;
    height: 55px;
    background: #e9f2ff;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #0d6efd;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold text-primary fs-4 ">
         PESBUK
        </span>

        <div class="d-flex gap-2">
            <a href="/views/login.php" class="btn btn-outline-primary btn-soft">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </a>
            <a href="/views/register.php" class="btn btn-primary btn-soft">
                <i class="bi bi-person-plus"></i> Daftar
            </a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="d-flex align-items-center" style="min-height: 85vh;">
    <div class="container">

        <div class="row align-items-center hero-card">

            <div class="col-md-6">
                <h1 class="hero-title">Selamat Datang di Pesbuk</h1>
                <p class="mt-3 opacity-75">
                    Tempat kamu berbagi cerita, menemukan teman baru, dan membangun koneksi digital.
                </p>

                <div class="mt-4 d-flex gap-3">
                    <a href="/views/register.php" class="btn btn-light text-primary btn-soft">
                        <i class="bi bi-rocket-takeoff"></i> Mulai
                    </a>
                    <a href="/views/login.php" class="btn btn-outline-light btn-soft">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </a>
                </div>
            </div>

            <div class="col-md-6 text-center mt-4 mt-md-0">
                <img src="img/connect.png" class="hero-img img-fluid">
            </div>

        </div>

    </div>
</section>

<!-- FEATURES -->
<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Kenapa Pilih Pesbuk?</h2>
            <p class="text-muted">Fitur sederhana tapi powerful</p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box mx-auto mb-3">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <h5 class="fw-bold">Berbagi Cerita</h5>
                    <p class="text-muted small">Posting pengalamanmu dengan mudah</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box mx-auto mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="fw-bold">Terhubung</h5>
                    <p class="text-muted small">Bangun relasi dengan orang lain</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box mx-auto mb-3">
                        <i class="bi bi-image-fill"></i>
                    </div>
                    <h5 class="fw-bold">Upload Mudah</h5>
                    <p class="text-muted small">Upload foto tanpa ribet dan cepat</p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="py-4 mt-5 border-top bg-white">
    <div class="container text-center">
        <span class="fw-bold text-primary fs-5">PESBUK</span>
        <p class="text-muted small mt-2 mb-0">
            &copy; <?php echo date("Y"); ?> Pesbuk Indonesia. <br>
            Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk koneksi yang lebih baik.
        </p>
    </div>
</footer>

</body>
</html>