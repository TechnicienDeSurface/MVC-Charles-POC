<?php

namespace App\Entity;

use App\Entity\Media;
use InvalidArgumentException;

/**
 * Représente un Livre, qui hérite des propriétés générales d'un Media.
 * C'est une entité simple caractérisée principalement par son nombre de pages.
 */
class Book extends Media
{
    /**
     * @var int Le nombre de pages du livre.
     */
    private int $nbPages;

    /**
     * Constructeur de la classe Book.
     * * Il appelle le constructeur de la classe parente (Media) pour initialiser 
     * le titre, l'auteur et la disponibilité.
     * * @param string $titre Le titre du livre.
     * @param string $auteur L'auteur du livre.
     * @param int $nbPages Le nombre de pages.
     * @param bool $disponible Indique si le livre est disponible à l'emprunt (par défaut à true).
     */
    public function __construct(string $titre, string $auteur, int $nbPages, bool $disponible = true)
    {
        // Initialise les propriétés héritées de Media (titre, auteur, disponible)
        parent::__construct($titre, $auteur, $disponible);
        
        $this->setNbPages($nbPages);
    }

    /**
     * Récupère le nombre de pages du livre.
     * * @return int
     */
    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    /**
     * Définit le nombre de pages du livre.
     * * @param int $nbPages Le nombre de pages. Doit être positif.
     * @return static Retourne l'instance actuelle (pour le chaînage).
     */
    public function setNbPages(int $nbPages): static
    {
        if ($nbPages <= 0) {
            throw new InvalidArgumentException("Le nombre de pages doit être supérieur à zéro.");
        }
        $this->nbPages = $nbPages;

        return $this;
    }

    /**
     * Récupère l'identifiant unique du livre.
     * NOTE: Cette propriété est héritée de la classe Media.
     * * @return int|null L'ID si défini, ou null.
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
