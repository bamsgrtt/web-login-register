<?php   
require_once '../classes/auth.php';
require_once '../config/Database.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
switch ($page) {
    case 'mahasiswa':
        $content = "../views/mahasiswa.php";
        $judul  = "Data Mahasiswa";
        break;
    case 'cuaca':
        $content = "../views/timeline.php";
        $judul  = "Informasi Cuaca";
        break;
    default:
        $content = "../views/postingan.php";
        $judul  = "Dashboard Utama";
}

$session = new Session();

// Proteksi
if (!$session->get('user')) {
    header("Location: ../index.php");
    exit;
}

$db = (new Database())->getConnection();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesbuk Timeline</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9) !important;
        }

        .card {
            border-radius: 18px;
            border: none;
            transition: all 0.2s ease;
        }

        .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e4e6eb;
        }

        .post-img {
            border-radius: 12px;
            height: 300px;
            width: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .caption {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        .feed-action {
            cursor: pointer;
            transition: 0.2s;
            border-radius: 8px;
        }

        .feed-action:hover {
            background: #f1f3f5;
            color: #0d6efd;
        }

        .post-input {
            background: #f0f2f5;
            border-radius: 30px;
            border: none;
            padding: 10px 15px;
        }

        .post-input:focus {
            background: #fff;
            box-shadow: 0 0 0 2px #0d6efd20;
        }

        #success-alert {
            position: fixed;
            top: 80px;
            /* Adjust based on your navbar height */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 90%;
            max-width: 500px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 bg-success text-white" style="min-height: 100vh;">
                <h4 class="p-3">Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link text-white border rounded-5 bg-info" href="main.php?page=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="main.php?page=mahasiswa">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="main.php?page=cuaca">Cek Cuaca</a></li>
                </ul>
            </nav>

            <main class="col-md-9 p-4">
                <?php 
                    // Memeriksa apakah file ada, jika ada maka tampilkan
                    if (file_exists($content)) {
                        include $content;
                    } else {
                        echo "<h2>Halaman Tidak Ditemukan!</h2>";
                    }
                ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(function() {
        var alertElement = document.getElementById('success-alert');
        if (alertElement) {
            var alert = new bootstrap.Alert(alertElement);
            alert.close();
        }
    }, 3000);
</script>

</body>

</html>