<?php

require_once 'classes/Buku.php';

$buku = new Buku();

$pesan = "";

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$data = $buku->find($id);

if (!$data) {
    die("Data buku tidak ditemukan!");
}

// Proses update
if (isset($_POST['update'])) {

    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = (int) $_POST['tahun'];
    $stok = (int) $_POST['stok'];

    if ($judul == "") {

        $pesan = "Judul buku tidak boleh kosong!";

    } elseif ($stok < 0) {

        $pesan = "Stok tidak boleh negatif!";

    } else {

        try {

            $buku->update(
                $id,
                $judul,
                $penulis,
                $tahun,
                $stok
            );

            header("Location: index.php?status=berhasil");
            exit;

        } catch (mysqli_sql_exception $e) {

            error_log($e->getMessage());
            $pesan = "Terjadi kesalahan saat mengubah buku.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
</head>

<body>

    <h1>Edit Data Buku</h1>

    <p><?= htmlspecialchars($pesan) ?></p>

    <form method="POST">

        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Judul Buku:</label><br>
        <input
            type="text"
            name="judul"
            value="<?= htmlspecialchars($data['judul']) ?>"
            required
        >

        <br><br>

        <label>Penulis:</label><br>
        <input
            type="text"
            name="penulis"
            value="<?= htmlspecialchars($data['penulis']) ?>"
            required
        >

        <br><br>

        <label>Tahun:</label><br>
        <input
            type="number"
            name="tahun"
            value="<?= htmlspecialchars((string)$data['tahun']) ?>"
        >

        <br><br>

        <label>Stok:</label><br>
        <input
            type="number"
            name="stok"
            min="0"
            value="<?= htmlspecialchars((string)$data['stok']) ?>"
        >

        <br><br>

        <button type="submit" name="update">
            Simpan Perubahan
        </button>

        <a href="index.php">Kembali</a>

    </form>

</body>
</html>