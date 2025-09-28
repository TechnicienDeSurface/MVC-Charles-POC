<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config/config.php';

$pdo = connectBD();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $ok = $stmt->execute(['id' => $id]);
    if ($ok) {
        $_SESSION['success'] = "Livre supprimé avec succès!";
        header("Location: ../main.php");
        exit;
    }
} else {
    header("Location: ../main.php");
    exit;
}