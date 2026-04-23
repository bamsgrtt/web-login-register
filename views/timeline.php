<?php
require_once '../classes/Session.php';
require_once '../classes/post.php';
require_once '../config/database.php';

$session = new Session();

// Proteksi
if (!$session->get('user')) {
    header("Location: login.php");
    exit;
}

$db = (new Database())->getConnection();
$postClass = new Post($db);

// ✅ HANDLE POST DULU
if (isset($_POST['post'])) {

    $user = $session->get('user');
    $user_id = $user['id'];
    $caption = htmlspecialchars($_POST['caption']);

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../img/" . $imageName);
    }

    $postClass->create($user_id, $caption, $imageName);

    header("Location: timeline.php");
    exit;
}

// ✅ BARU ambil data
$posts = $postClass->getAll();

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

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
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
    </style>
</head>

<body>

    <nav class="navbar navbar-light bg-white shadow-sm sticky-top mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold text-primary fs-4">PESBUK</span>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-semibold">
                    Halo, <?php echo $session->get('user')['username']; ?>
                </span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm p-3 mb-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mb-3">
                            <input type="text" name="caption"
                                class="form-control post-input"
                                placeholder="Apa yang kamu pikirkan?">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <input type="file" name="image"
                                class="form-control form-control-sm w-50">

                            <button type="submit" name="post"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">

                                <!-- Header -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                        style="width:45px; height:45px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo $post['username']; ?></div>
                                        <small class="text-muted"><?php echo $post['created_at']; ?></small>
                                    </div>
                                </div>

                                <!-- Caption -->
                                <div class="caption mb-3">
                                    <?php echo $post['caption']; ?>
                                </div>

                                <!-- Image -->
                                <?php if (!empty($post['image'])): ?>
                                    <img src="../img/<?php echo $post['image']; ?>"
                                        class="img-fluid post-img w-100 mb-3">
                                <?php endif; ?>

                                <!-- Actions -->
                                <div class="d-flex justify-content-between border-top pt-2 text-muted small fw-semibold">
                                    <div class="feed-action px-3 py-2 w-100 text-center">
                                        <i class="bi bi-hand-thumbs-up"></i> Suka
                                    </div>
                                    <div class="feed-action px-3 py-2 w-100 text-center">
                                        <i class="bi bi-chat"></i> Komentar
                                    </div>
                                    <div class="feed-action px-3 py-2 w-100 text-center">
                                        <i class="bi bi-share"></i> Bagikan
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted mt-4">
                    Belum ada postingan 😶
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>