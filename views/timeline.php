<?php
require_once '../classes/Session.php';
require_once '../classes/post.php';
require_once '../config/database.php';
require_once '../classes/Upload.php';

$session = new Session();

// Proteksi
if (!$session->get('user')) {
    header("Location: login.php");
    exit;
}

$db = (new Database())->getConnection();
$postClass = new Post($db);
$upload = new Upload();

//  HANDLE POST 
if (isset($_POST['post'])) {

    $user = $session->get('user');
    $user_id = $user['id'];
    $caption = htmlspecialchars($_POST['caption']);

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $imageName = $upload->uploadImage($_FILES['image']);
    }

    $postClass->create($user_id, $caption, $imageName);

    header("Location: timeline.php");
    exit;
}

// ✅ BARU ambil data
$posts = $postClass->getAll();

if (isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'];
    $current_user_id = $session->get('user')['id'];
    $postClass->delete($post_id);
    header("Location: timeline.php");
    exit;
}

if (isset($_POST['update_post'])) {
    $post_id = $_POST['post_id'];
    $caption = htmlspecialchars($_POST['caption']);
    $user_id = $session->get('user')['id'];

    // Cek gambar baru
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = $upload->uploadImage($_FILES['image']);
    } else {
        // Jika tidak ada gambar baru, ambil path gambar lama dari database
        $oldPost = $postClass->getById($post_id, $user_id);
        $imageName = $oldPost['image'];
    }

    $postClass->edit($post_id, $user_id, $caption, $imageName);

    header("Location: timeline.php");
    exit;
}
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
    <?php if ($session->get('success')): ?>
        <div id="success-alert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $session->get('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php $session->remove('success'); ?>
    <?php endif; ?>

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

                                    <?php if ($post['user_id'] == $session->get('user')['id']): ?>
                                        <div class="ms-auto d-flex gap-2">

                                            <!-- Tombol Edit -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal<?= $post['id'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Tombol Delete -->
                                            <form method="POST" onsubmit="return confirm('Yakin ingin menghapus postingan ini?');">
                                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                <button type="submit" name="delete_post" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    <?php endif; ?>
                                    <div class="modal fade" id="editModal<?= $post['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Postingan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Caption</label>
                                                            <textarea name="caption" class="form-control" rows="3" required><?= htmlspecialchars($post['caption']) ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ganti Gambar (Opsional)</label>
                                                            <input type="file" name="image" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_post" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Caption -->
                                <div class="caption mb-3">
                                    <?php echo $post['caption']; ?>
                                </div>

                                <!-- Image -->
                                <?php if (!empty($post['image'])): ?>
                                    <img src="../<?php echo $post['image']; ?>"
                                        class="img-fluid post-img w-100 mb-3">
                                <?php endif; ?>

                                <div class="d-flex border-top pt-2 text-muted small fw-semibold">

                                    <div class="feed-action flex-fill text-center d-flex flex-column align-items-center py-2">
                                        <i class="bi bi-hand-thumbs-up mb-1"></i>
                                        <span>Suka</span>
                                    </div>

                                    <div class="feed-action flex-fill text-center d-flex flex-column align-items-center py-2">
                                        <i class="bi bi-chat mb-1"></i>
                                        <span>Komentar</span>
                                    </div>

                                    <div class="feed-action flex-fill text-center d-flex flex-column align-items-center py-2">
                                        <i class="bi bi-share mb-1"></i>
                                        <span>Bagikan</span>
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

    <footer class="py-4 mt-5 border-top bg-white">
        <div class="container text-center">
            <span class="fw-bold text-primary fs-5">PESBUK</span>
            <p class="text-muted small mt-2 mb-0">
                &copy; <?php echo date("Y"); ?> Pesbuk Indonesia. <br>
                Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk koneksi yang lebih baik.
            </p>
        </div>
    </footer>
    <script>
        setTimeout(function() {
            var alertElement = document.getElementById('success-alert');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 3000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>