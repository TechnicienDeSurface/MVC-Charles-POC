<?php

include '../config/config.php';

use App\Entity\Movie;

session_start();

if (!isset($_SESSION)) {
    header("Location: ../index.php");
    exit();
}

try {
    if ($id <= 0) {
        throw new \InvalidArgumentException("ID invalide.");
    }

    // 2. Chercher le média (le Film) dans la base de données via le Repository
    $movie = $this->MovieRepository->find($id);

    if (!$movie) {
        $_SESSION['error'] = "Film non trouvé.";
    } else {

        // 3. Déclencher la fonction `rendre()` de l'entité Media/Movie
        $movie->rendre();

        // 4. Sauvegarder le changement d'état (disponible = true) dans la base de données
        if ($this->MovieRepository->update($movie)) {
            $_SESSION['success'] = "Film rendu avec succès!";
            header('Location: ../main.php');
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour de l'état.";
        }
    }
} catch (\Exception $e) {
    $_SESSION['error'] = "Erreur de retour : " . $e->getMessage();
}

exit();
