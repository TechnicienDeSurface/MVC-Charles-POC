<?php

require '../../config/config.php';

$pdo = connectBD();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM media WHERE id = :id");
    $ok = $stmt->execute(['id' => $id]);
    if ($ok) {
        header("Location: ../main.php");
        exit;
    }
} else {
    header("Location: ../main.php");
    exit;
}