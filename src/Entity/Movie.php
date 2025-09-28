<?php

namespace App\Entity;

use App\Entity\Media;
use App\Enum\Genre; // Assurez-vous que cette énumération existe
use InvalidArgumentException;

/**
 * Représente un Film, qui hérite des propriétés générales d'un Media.
 * Il est caractérisé par sa durée et son genre (via une Enum).
 */
class Movie extends Media
{
    /**
     * @var float La durée du film en heures (ou une autre unité, ex: minutes/60).
     */
    private float $duration;

    /**
     * @var Genre Le genre du film, utilisant une énumération (Enum).
     */
    private Genre $genre;

    /**
     * Constructeur de la classe Movie.
     * * @param string $titre Le titre du film.
     * @param string $auteur Le réalisateur (auteur) du film.
     * @param float $duration La durée du film.
     * @param Genre $genre Le genre du film.
     * @param bool $disponible Indique si le film est disponible à l'emprunt (par défaut à true).
     */
    public function __construct(string $titre, string $auteur, float $duration, Genre $genre, bool $disponible = true)
    {
        // Initialise les propriétés héritées de Media (titre, auteur, disponible)
        parent::__construct($titre, $auteur, $disponible);
        
        $this->setDuration($duration);
        $this->setGenre($genre);
    }

    /**
     * Récupère la durée du film.
     * * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * Définit la durée du film.
     * * @param float $duration La durée du film.
     * @return static
     */
    public function setDuration(float $duration): static
    {
        if ($duration <= 0) {
             throw new InvalidArgumentException("La durée doit être positive.");
        }
        $this->duration = $duration;
        
        return $this;
    }

    /**
     * Récupère le genre du film.
     * * @return Genre
     */
    public function getGenre(): Genre
    {
        return $this->genre;
    }

    /**
     * Définit le genre du film.
     * * @param Genre $genre
     * @return static
     */
    public function setGenre(Genre $genre): static
    {
        $this->genre = $genre;
        
        return $this;
    }
}
