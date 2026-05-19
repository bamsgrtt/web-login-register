<?php
require_once '../classes/posts.php';
require_once '../classes/upload.php';

    $postClass = new Post($db);
    $upload = new Upload();


    if (isset($_POST['post'])) {

        $user = $session->get('user');
        $user_id = $user['id'];
        $caption = htmlspecialchars($_POST['caption']);

        $imageName = null;

        if (!empty($_FILES['image']['name'])) {
            $imageName = $upload->uploadImage($_FILES['image']);
        }

        $postClass->create($user_id, $caption, $imageName);

        header("Location: ../index.php");
        exit;
    }

   
    $posts = $postClass->getAll();

    if (isset($_POST['delete_post'])) {
        $post_id = $_POST['post_id'];
        $current_user_id = $session->get('user')['id'];
        $postClass->delete($post_id);
        header("Location: ../index.php");
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

    header("Location: ../index.php");
    exit;
    }
?>   
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
