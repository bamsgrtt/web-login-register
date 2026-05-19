<?php 
class MahasiswaController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read_data() {
        $query = "SELECT * FROM mahasiswa ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataById($id) {
        $query = "SELECT * FROM mahasiswa WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Returns array or null
    }

    public function store($data) {
        $query = "INSERT INTO mahasiswa (nim, nama, prodi) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $data['nim'], $data['nama'], $data['prodi']);
        
        if ($stmt->execute()) {
            return $this->getDataById($this->conn->insert_id);
        }
        return false;
    }

    public function update($id, $data) {
        if (!$this->getDataById($id)) return false;

        $query = "UPDATE mahasiswa SET nim = ?, nama = ?, prodi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $data['nim'], $data['nama'], $data['prodi'], $id);
        
        return $stmt->execute() ? $this->getDataById($id) : false;
    }

    public function destroy($id) {
        if (!$this->getDataById($id)) return false;

        $query = "DELETE FROM mahasiswa WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute(); // Returns true on success
    }
}