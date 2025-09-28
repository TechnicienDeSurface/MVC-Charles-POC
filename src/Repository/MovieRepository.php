<?php

namespace App\Repository;

use \PDO;
use \Exception;
use App\Entity\Movie;
use App\Enum\Genre;

/**
 * Gère les opérations de persistance (CRUD) pour l'entité Movie.
 */
class MovieRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Met à jour l'état de disponibilité d'un film.
     * @param int $id L'identifiant du film.
     * @param bool $isAvailable Le nouvel état (true pour disponible, false pour emprunté).
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function updateAvailability(int $id, bool $isAvailable): bool
    {
        // 1. Définir la requête SQL pour mettre à jour uniquement la colonne 'disponible'
        $sql = "UPDATE media SET disponible = :disponible WHERE id = :id AND type = 'movie'";

        // 2. Préparer la requête
        $stmt = $this->pdo->prepare($sql);

        // 3. Exécuter avec les paramètres
        $success = $stmt->execute([
            ':disponible' => $isAvailable ? 1 : 0, // Convertir le booléen en entier (1 ou 0) pour la DB
            ':id' => $id
        ]);

        // 4. Vérifier si l'opération a affecté exactement une ligne
        // Ceci garantit que la mise à jour a eu lieu et que le film existait bien
        return $success && $stmt->rowCount() === 1;
    }  

     /**
     * Méthode utilitaire pour créer un objet Movie à partir des données de la BDD.
     * @param array $data Tableau associatif contenant les données du film.
     * @return Movie
     * @throws Exception Si la valeur de genre est invalide.
     */
    private function hydrate(array $data): Movie
    {
        // 1. Extraction des données
        $titre = $data['titre'] ?? 'Titre Inconnu';
        $auteur = $data['auteur'] ?? 'Auteur Inconnu';
        $disponible = (bool)($data['disponible'] ?? true);
        
        // Données spécifiques au film (Vérifiez si la colonne est 'duration' ou 'duree' dans votre table 'movies')
        $duration = (float)($data['duration'] ?? 0.0);
        
        // Conversion de la chaîne de BDD en objet Enum Genre
        try {
            // Assurez-vous que la colonne 'genre' de votre BDD correspond aux valeurs de votre Enum
            $genre = Genre::from($data['genre']); 
        } catch (\ValueError $e) {
            throw new Exception("Valeur de genre invalide pour le film ID " . ($data['id'] ?? 'inconnu'));
        }
        
        // 2. Instanciation (Injection via constructeur)
        $movie = new Movie(
            $titre,
            $auteur,
            $duration,
            $genre,
            $disponible
        );
        
        return $movie;
    }

    /**
     * Récupère un film par son ID.
     * @param int $id L'identifiant du film (correspondant à media.id).
     * @return Movie|null L'objet Movie ou null si non trouvé.
     */
    public function find(int $id): ?Movie
    {
        // Utilisation d'une jointure (LEFT JOIN) pour récupérer les données spécifiques à 'movies'
        // et le champ 'type_media' pour garantir que c'est bien un film.
        $sql = "
            SELECT 
                m.*, 
                mo.duration, 
                mo.genre 
            FROM media m
            LEFT JOIN movies mo ON m.id = mo.id
            WHERE m.id = :id AND m.type_media = 'movie'
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->hydrate($data);
    }

}
