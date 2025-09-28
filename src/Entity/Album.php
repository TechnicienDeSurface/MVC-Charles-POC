<?php
namespace App\Entity;

use App\Entity\Song;
use App\Entity\Media;
use InvalidArgumentException;

/**
 * Représente un Album musical, qui hérite des propriétés générales d'un Media.
 * Un Album contient des informations spécifiques comme le nombre de pistes, l'éditeur
 * et une collection de pistes (Song).
 */
class Album extends Media
{
    /**
     * @var int Le nombre total de pistes (chansons) sur l'album.
     */
    private int $trackNumber;

    /**
     * @var string Le nom de l'éditeur (label) de l'album.
     */
    private string $editor;

    /**
     * @var array<Song> La liste des objets Song (pistes) contenus dans cet album.
     */
    private array $songs;

    /**
     * Constructeur de la classe Album.
     * * @param string $titre Le titre de l'album.
     * @param string $auteur L'artiste ou le groupe.
     * @param int $trackNumber Le nombre de pistes sur l'album.
     * @param string $editor L'éditeur (label) de l'album.
     * @param bool $disponible Indique si l'album est disponible à l'emprunt (par défaut à true).
     */
    public function __construct(string $titre, string $auteur, int $trackNumber, string $editor, bool $disponible = true)
    {
        // Les propriétés $titre, $auteur et $disponible sont définies dans la classe parente Media
        // Assurez-vous d'appeler parent::__construct si la classe Media en a un pour initialiser ses propriétés.
        // Sinon, si les propriétés sont publiques ou protégées, cette affectation est correcte :
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->disponible = $disponible;
        
        $this->trackNumber = $trackNumber;
        $this->editor = $editor;
        $this->songs = [];
    }

    /**
     * Récupère le nombre total de pistes sur l'album.
     * * NOTE: La méthode originale avait un paramètre en trop. Il a été supprimé.
     * * @return int
     */
    public function getTrackNumber(): int
    {
        return $this->trackNumber;
    }

    /**
     * Définit le nombre total de pistes sur l'album.
     * * @param int $trackNumber
     * @return void
     */
    public function setTrackNumber(int $trackNumber): void
    {
        if ($trackNumber <= 0) {
            throw new InvalidArgumentException("Le nombre de pistes doit être supérieur à zéro.");
        }
        $this->trackNumber = $trackNumber;
    }

    /**
     * Récupère le nom de l'éditeur (label).
     * * NOTE: La méthode originale avait un paramètre en trop. Il a été supprimé.
     * * @return string
     */
    public function getEditor(): string
    {
        return $this->editor;
    }

    /**
     * Définit le nom de l'éditeur (label).
     * * @param string $editor
     * @return void
     */
    public function setEditor(string $editor): void
    {
        $this->editor = $editor;
    }

    /**
     * Récupère la liste des pistes (objets Song) de l'album.
     * * @return array<Song>
     */
    public function getSongs(): array
    {
        return $this->songs;
    }

    /**
     * Ajoute une piste (objet Song) à l'album.
     * * @param Song $song
     * @return void
     */
    public function addSong(Song $song): void
    {
        $this->songs[] = $song;
    }

    /**
     * Supprime une piste de l'album via son index dans le tableau.
     * * @param int $index L'index de la piste à supprimer.
     * @return bool Vrai si la piste a été supprimée, Faux sinon.
     */
    public function removeSong(int $index): bool
    {
        if (isset($this->songs[$index])) {
            unset($this->songs[$index]);
            // Ré-indexer le tableau après suppression pour éviter les trous
            $this->songs = array_values($this->songs);
            return true;
        }
        return false;
    }
}
