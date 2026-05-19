<?php
require_once '../config/Database.php';
require_once '../classes/MahasiswaController.php';

$database = new Database();
$db = $database->getConnection();
$mahasiswa = new MahasiswaController($db);

$data = [];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $mhs = $mahasiswa->getDataById($id);
            if ($mhs) {
                $data = [
                    'status' => 'success',
                    'method' => 'GET',
                    'message' => 'Data berhasil diambil',
                    'data' => $mhs
                ];
            } else {
                $data = ['status' => 'error', 'message' => 'Data tidak ditemukan'];
                http_response_code(404);
            }
        } else {
            // Logic for getting all data
            $all = $mahasiswa->read_data()->fetch_all(MYSQLI_ASSOC);
            $data = [
                'status' => 'success',
                'count' => count($all),
                'data' => $all
            ];
        }
        break;

    case 'POST':
    $json = file_get_contents('php://input');
    $input = json_decode($json, true);
    $results = [];

    // Cek apakah input kosong
    if (!$input) {
        $data = ['status' => 'error', 'message' => 'Data JSON tidak valid atau kosong'];
        http_response_code(400);
        break;
    }

    // Cek apakah data yang dikirim adalah array banyak (bulk)
    if (isset($input[0]) && is_array($input[0])) {
        foreach ($input as $row) {
            $post = [
                'nim'   => $row['nim'] ?? null,   // Gunakan $row, bukan $input
                'nama'  => $row['nama'] ?? null,  // Gunakan $row, bukan $input
                'prodi' => $row['prodi'] ?? null, // Gunakan $row, bukan $input
            ];
            $results[] = $mahasiswa->store($post); 
        }
        $data = [
            'status' => 'success',
            'message' => 'Data massal berhasil diproses',
            'data' => $results
        ];
        http_response_code(201);
    } else {
        // Jika hanya kirim satu data (bukan array nested)
        $mhs = $mahasiswa->store($input); 
        if ($mhs) {
            $data = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $mhs
            ];
            http_response_code(201);
        } else {
            $data = ['status' => 'error', 'message' => 'Gagal menyimpan data'];
            http_response_code(400);
        }
    }
    break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = $_PUT["id"] ?? null;
        $put = [
            'nim' => $_PUT['nim'] ?? null,
            'nama' => $_PUT['nama'] ?? null,
            'prodi' => $_PUT['prodi'] ?? null,
        ];

        if ($mhs = $mahasiswa->update($id, $put)) {
            $data = [
                'status' => 'success',
                'method' => 'PUT',
                'message' => 'Data berhasil diupdate',
                'data' => $mhs,
            ];
        } else {
            $data = ['status' => 'error', 'message' => 'Gagal update: ID tidak ditemukan'];
            http_response_code(400);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($mahasiswa->destroy($id)) {
            $data = [
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ];
        } else {
            $data = ['status' => 'error', 'message' => 'Gagal menghapus: ID tidak ditemukan'];
            http_response_code(400);
        }
        break;

    default:
        $data = ['status' => 'error', 'message' => 'Metode tidak ditemukan'];
        http_response_code(405);
        break;
}

header('Content-Type: application/json');
echo json_encode($data);