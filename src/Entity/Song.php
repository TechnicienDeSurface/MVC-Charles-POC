<?php

namespace App\Entity;

use InvalidArgumentException;

/**
 * Représente une Chanson ou une piste audio.
 * Elle est caractérisée par son titre, sa durée, une note, et peut être liée à un Album.
 */
class Song
{
    /**
     * @var int|null L'identifiant unique de la chanson en base de données.
     */
    protected ?int $id = null;

    /**
     * @var string Le titre de la chanson.
     */
    protected string $title;

    /**
     * @var int La durée de la chanson en secondes.
     */
    protected int $duration;

    /**
     * @var int La note donnée à la chanson (doit être comprise entre 0 et 5).
     */
    protected int $note;

    /**
     * @var Album|null L'album auquel cette chanson appartient (relation ManyToOne).
     */
    protected ?Album $album = null;

    /**
     * Constructeur de la classe Song.
     * * @param string $title Le titre de la chanson (obligatoire).
     * @param int $duration La durée de la chanson en secondes (obligatoire).
     * @param int $note La note initiale (par défaut à 0).
     */
    public function __construct(string $title, int $duration, int $note = 0)
    {
        $this->setTitle($title);
        $this->setDuration($duration);
        $this->setNote($note);
    }

    /**
     * Récupère l'identifiant unique de la chanson.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le titre de la chanson.
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la chanson.
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Récupère la durée de la chanson en secondes.
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Définit la durée de la chanson en secondes.
     * @param int $duration
     * @return static
     */
    public function setDuration(int $duration): static
    {
        if ($duration < 0) {
            throw new InvalidArgumentException("La durée ne peut pas être négative.");
        }
        $this->duration = $duration;
        return $this;
    }

    /**
     * Récupère la note attribuée à la chanson.
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * Définit la note de la chanson (doit être entre 0 et 5).
     * @param int $note
     * @return static
     * @throws InvalidArgumentException Si la note n'est pas comprise entre 0 et 5.
     */
    public function setNote(int $note): static 
    {
        // Validation de la note (entre 0 et 5)
        if ($note >= 0 && $note <= 5) {
            $this->note = $note;
        } else {
            throw new InvalidArgumentException("La note doit être comprise entre 0 et 5");
        }
        return $this; // Ajouté pour permettre le chaînage
    }

    /**
     * Récupère l'Album auquel cette chanson est associée.
     * @return Album|null
     */
    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    /**
     * Associe la chanson à un Album.
     * @param Album|null $album
     * @return static
     */
    public function setAlbum(?Album $album): static
    {
        $this->album = $album;
        return $this;
    }
}
