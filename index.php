<?php

require_once 'classes/Buku.php';

$buku = new Buku();

$pesan = "";

// Proses tambah buku
if (isset($_POST['tambah'])) {

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

            $buku->create($judul, $penulis, $tahun, $stok);

            header("Location: index.php?status=berhasil");
            exit;

        } catch (mysqli_sql_exception $e) {

            error_log($e->getMessage());
            $pesan = "Terjadi kesalahan saat menyimpan buku.";
        }
    }
}

// Pesan berhasil
if (isset($_GET['status']) && $_GET['status'] == 'berhasil') {
    $pesan = "Buku berhasil ditambahkan!";
}

// Fitur pencarian
$keyword = $_GET['cari'] ?? '';

$dataBuku = $buku->all($keyword);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Perpustakaan</title>
</head>

<body>

    <h1>Mini Proyek Perpustakaan</h1>

    <hr>

    <h2>Tambah Buku</h2>

    <?php if ($pesan != ""): ?>
        <p>
            <strong>
                <?= htmlspecialchars($pesan) ?>
            </strong>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Judul Buku:</label><br>
        <input type="text" name="judul" required>
        <br><br>

        <label>Penulis:</label><br>
        <input type="text" name="penulis" required>
        <br><br>

        <label>Tahun:</label><br>
        <input type="number" name="tahun">
        <br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" min="0" value="0">
        <br><br>

        <button type="submit" name="tambah">
            Tambah Buku
        </button>

    </form>

    <hr>

    <h2>Daftar Buku</h2>

    <!-- Form pencarian -->
    <form method="GET">

        <input
            type="text"
            name="cari"
            placeholder="Cari judul buku..."
            value="<?= htmlspecialchars($keyword) ?>"
        >

        <button type="submit">Cari</button>

        <a href="index.php">Reset</a>

    </form>

    <br>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($dataBuku) > 0): ?>

            <?php foreach ($dataBuku as $row): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars((string)$row['id']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['judul']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['penulis']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string)$row['tahun']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string)$row['stok']) ?>
                    </td>

                    <td>

                        <a href="edit.php?id=<?= $row['id'] ?>">
                            Edit
                        </a>

                        |

                        <form method="POST"
                              action="hapus.php"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin ingin menghapus buku ini?')">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id'] ?>"
                            >

                            <button type="submit">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">
                    Data buku tidak ditemukan.
                </td>
            </tr>

        <?php endif; ?>

    </table>

</body>
</html>