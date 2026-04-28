<?php

class Upload {

    private $folder;

    public function __construct($folder = null) {

        // 🔥 FIX PATH ABSOLUTE (WAJIB DI DOCKER)
        $this->folder = __DIR__ . "/../uploads/posts/";

        if (!is_dir($this->folder)) {
            if (!mkdir($this->folder, 0775, true)) {
                die("Gagal membuat folder upload. Cek permission Docker.");
            }
        }
    }

    public function uploadImage($file) {

        if ($file['error'] !== 0) {
            return false;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return false;
        }

        $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;

        $target = $this->folder . $newName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return "uploads/posts/" . $newName;
        }

        return false;
    }
}