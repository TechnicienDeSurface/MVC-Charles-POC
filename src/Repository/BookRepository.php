<?php

namespace App\Repository;

use App\Entity\Book;
require_once __DIR__ . '/../config/database.php';

class BookRepository 
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = connectBD();
    }

    /**
     * Créer un nouveau livre en base de données
     */
    public function add(Book $book): bool
    {
        try {
            $sql = "INSERT INTO books (title, author, created_at, updated_at) 
                    VALUES (:title, :author, NOW(), NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':title', $book->getTitre());
            $stmt->bindValue(':author', $book->getAuteur());

            return false;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création : " . $e->getMessage());
        }
    }

    /**
     * Trouver un livre par son ID
     */
    public function find(int $id): ?Book
    {
        try {
            $sql = "SELECT * FROM books WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($data) {
                return $this->createBookFromData($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la recherche : " . $e->getMessage());
        }
    }

    /**
     * Récupérer tous les livres
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM books ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $books = [];
            while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $books[] = $this->createBookFromData($data);
            }
            
            return $books;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un livre
     */
    public function update(Book $book): bool
    {
        try {
            if (!$book->getId()) {
                throw new \Exception("Impossible de mettre à jour un livre sans ID");
            }
            
            $sql = "UPDATE books 
                    SET title = :title, author = :author
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':title', $book->getTitre());
            $stmt->bindValue(':author', $book->getAuteur());
            $stmt->bindValue(':id', $book->getId());
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    /**
     * Supprimer un livre par son ID
     */
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM books WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    /**
     * Vérifier si un livre existe
     */
    public function exists(int $id): bool 
    {
        try {
            $sql = "SELECT COUNT(*) FROM books WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la vérification : " . $e->getMessage());
        }
    }

    /**
     * Méthode utilitaire pour créer un objet Book à partir des données de la BDD
     */
    private function createBookFromData(array $data): Book
    {
        $book = new Book($data['title'], $data['author'], $data['nbPages']);
        return $book;
    }
}