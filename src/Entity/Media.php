<?php

namespace App\Entity;

use Exception; // Utilisation de la classe de base pour les exceptions

class Media
{
    /**
     * @var int|null L'identifiant unique du média en base de données.
     */
    protected ?int $id = null;

    /**
     * @var string|null Le titre du média.
     */
    protected ?string $titre;

    /**
     * @var string|null Le nom de l'auteur, artiste ou réalisateur du média.
     */
    protected ?string $auteur;

    /**
     * @var bool|null Indique si le média est actuellement disponible à l'emprunt (true) ou non (false).
     */
    protected ?bool $disponible;

    /**
     * Constructeur pour initialiser les propriétés de base.
     * Les classes filles (Book, Album) doivent appeler ce constructeur.
     * @param string $titre Le titre initial.
     * @param string $auteur L'auteur initial.
     * @param bool $disponible L'état de disponibilité initial.
     */
    public function __construct(string $titre, string $auteur, bool $disponible = true)
    {
        // L'ID est généralement défini par la base de données
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->disponible = $disponible;
    }

    /**
     * Récupère l'identifiant unique du média.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le titre du média.
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Définit le titre du média.
     * @param string $titre
     * @return static
     */
    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Récupère le nom de l'auteur, artiste ou réalisateur.
     * @return string|null
     */
    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    /**
     * Définit le nom de l'auteur, artiste ou réalisateur.
     * @param string $auteur
     * @return static
     */
    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Vérifie si le média est disponible.
     * @return bool|null
     */
    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    /**
     * Définit l'état de disponibilité du média.
     * @param bool $disponible
     * @return static
     */
    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Tente d'emprunter le média.
     * @return bool Vrai si l'emprunt a réussi.
     * @throws Exception Si le média n'est pas disponible.
     */
    public function emprunter(): bool
    {
        if ($this->disponible) {
            $this->disponible = false;
            return true;
        } else {
            // Lève une exception pour que le contrôleur puisse la capturer et informer l'utilisateur.
            throw new Exception("Le média n'est pas disponible.");
        }
    }

    /**
     * Marque le média comme rendu (disponible).
     * @return void
     */
    public function rendre(): void
    {
        $this->disponible = true;
    }
}
