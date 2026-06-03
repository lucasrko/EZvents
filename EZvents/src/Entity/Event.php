<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 40)]
    private ?string $categorie = null;

    #[ORM\Column]
    private ?\DateTime $date_heure = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom_equipe_1 = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom_equipe_2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo_equipe_1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo_equipe_2 = null;

    #[ORM\Column(length: 255)]
    private ?string $capacite = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?User $organisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDateHeure(): ?\DateTime
    {
        return $this->date_heure;
    }

    public function setDateHeure(\DateTime $date_heure): static
    {
        $this->date_heure = $date_heure;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNomEquipe1(): ?string
    {
        return $this->nom_equipe_1;
    }

    public function setNomEquipe1(?string $nom_equipe_1): static
    {
        $this->nom_equipe_1 = $nom_equipe_1;

        return $this;
    }

    public function getNomEquipe2(): ?string
    {
        return $this->nom_equipe_2;
    }

    public function setNomEquipe2(?string $nom_equipe_2): static
    {
        $this->nom_equipe_2 = $nom_equipe_2;

        return $this;
    }

    public function getLogoEquipe1(): ?string
    {
        return $this->logo_equipe_1;
    }

    public function setLogoEquipe1(?string $logo_equipe_1): static
    {
        $this->logo_equipe_1 = $logo_equipe_1;

        return $this;
    }

    public function getLogoEquipe2(): ?string
    {
        return $this->logo_equipe_2;
    }

    public function setLogoEquipe2(?string $logo_equipe_2): static
    {
        $this->logo_equipe_2 = $logo_equipe_2;

        return $this;
    }

    public function getCapacite(): ?string
    {
        return $this->capacite;
    }

    public function setCapacite(string $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getOrganisateur(): ?user
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?user $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
