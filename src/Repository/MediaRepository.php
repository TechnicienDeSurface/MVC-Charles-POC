<?php

namespace App\Repository;
use App\Entity\Media;
use \PDO;   

class MediaRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les médias de la base de données et les retourne en tant que tableaux associatifs bruts.
     * @return array<array> Tableau de tous les enregistrements de la table 'media'.
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM media");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
