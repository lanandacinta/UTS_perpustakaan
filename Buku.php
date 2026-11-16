<?php

require_once __DIR__ . '/../config/Database.php';

class Buku extends Database {

    // Menampilkan semua buku dan fitur pencarian
    public function all($keyword = "") {

        $sql = "SELECT * FROM buku
                WHERE judul LIKE ?
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        $cari = "%" . $keyword . "%";

        $stmt->bind_param("s", $cari);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    // Mencari buku berdasarkan ID
    public function find($id) {

        $sql = "SELECT * FROM buku WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }

    // Menambahkan buku
    public function create($judul, $penulis, $tahun, $stok) {

        $sql = "INSERT INTO buku
                (judul, penulis, tahun, stok)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssii",
            $judul,
            $penulis,
            $tahun,
            $stok
        );

        $stmt->execute();

        $hasil = $stmt->affected_rows;

        $stmt->close();

        return $hasil;
    }

    // Mengedit buku
    public function update($id, $judul, $penulis, $tahun, $stok) {

        $sql = "UPDATE buku
                SET judul = ?, penulis = ?,
                    tahun = ?, stok = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssiii",
            $judul,
            $penulis,
            $tahun,
            $stok,
            $id
        );

        $stmt->execute();

        $hasil = $stmt->affected_rows;

        $stmt->close();

        return $hasil;
    }

    // Menghapus buku
    public function delete($id) {

        $sql = "DELETE FROM buku WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $hasil = $stmt->affected_rows;

        $stmt->close();

        return $hasil;
    }
}