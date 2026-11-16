<?php

require_once 'classes/Buku.php';

$buku = new Buku();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {

        try {

            $buku->delete($id);

        } catch (mysqli_sql_exception $e) {

            error_log($e->getMessage());
        }
    }
}

header("Location: index.php");
exit;

?>