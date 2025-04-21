<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\HebergementRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
#[ORM\Table(name: 'hebergement')]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_hebergement = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ ]+$/",
        message: "Le nom doit contenir uniquement des lettres et espaces"
    )]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    
    private ?string $adresse = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^[0-9]{8,15}$/",
        message: "Le numéro doit contenir entre 8 et 15 chiffres"
    )]
    private ?string $telephone = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide")]
    #[Assert\Email(
        message: "L'adresse email '{{ value }}' n'est pas valide"
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|tn)$/',
        message: "L'email doit être au format nom@domaine.com ou nom@domaine.tn"
    )]
    private ?string $email = null;
    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "La capacité ne peut pas être vide")]
    #[Assert\GreaterThan(
        value: 0,
        message: "La capacité doit être supérieure à 0 personnes"
    )]
    private ?int $capacite = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "Le tarif par nuit ne peut pas être vide")]
    #[Assert\GreaterThan(
        value: 0,
        message: "Le tarif par nuit doit être supérieur à 0 DT"
    )]
    private ?float $tarif_nuit = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Url(
        message: "L'URL de l'image '{{ value }}' n'est pas valide",
        protocols: ['http', 'https']
    )]
    private ?string $image_url = null;

    #[ORM\OneToMany(targetEntity: HebergementPhoto::class, mappedBy: 'hebergement')]
    private Collection $hebergementPhotos;

    #[ORM\OneToMany(targetEntity: ReservationHebergement::class, mappedBy: 'hebergement')]
    private Collection $reservationHebergements;

    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'hebergement')]
    private Collection $services;

    public function __construct()
    {
        $this->hebergementPhotos = new ArrayCollection();
        $this->reservationHebergements = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    // Getters and Setters remain the same, just adding validation above
    public function getId_hebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function setId_hebergement(int $id_hebergement): self
    {
        $this->id_hebergement = $id_hebergement;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function getTarif_nuit(): ?float
    {
        return $this->tarif_nuit;
    }

    public function setTarif_nuit(float $tarif_nuit): self
    {
        $this->tarif_nuit = $tarif_nuit;
        return $this;
    }

    public function getImage_url(): ?string
    {
        return $this->image_url;
    }

    public function setImage_url(?string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    public function getHebergementPhotos(): Collection
    {
        if (!$this->hebergementPhotos instanceof Collection) {
            $this->hebergementPhotos = new ArrayCollection();
        }
        return $this->hebergementPhotos;
    }

    public function addHebergementPhoto(HebergementPhoto $hebergementPhoto): self
    {
        if (!$this->getHebergementPhotos()->contains($hebergementPhoto)) {
            $this->getHebergementPhotos()->add($hebergementPhoto);
        }
        return $this;
    }

    public function removeHebergementPhoto(HebergementPhoto $hebergementPhoto): self
    {
        $this->getHebergementPhotos()->removeElement($hebergementPhoto);
        return $this;
    }

    public function getReservationHebergements(): Collection
    {
        if (!$this->reservationHebergements instanceof Collection) {
            $this->reservationHebergements = new ArrayCollection();
        }
        return $this->reservationHebergements;
    }

    public function addReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        if (!$this->getReservationHebergements()->contains($reservationHebergement)) {
            $this->getReservationHebergements()->add($reservationHebergement);
        }
        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        $this->getReservationHebergements()->removeElement($reservationHebergement);
        return $this;
    }

    // Corrected method names to follow camelCase convention
    public function getIdHebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function getTarifNuit(): ?float
    {
        return $this->tarif_nuit;
    }

    public function setTarifNuit(float $tarif_nuit): self
    {
        $this->tarif_nuit = $tarif_nuit;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setHebergement($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getHebergement() === $this) {
                $service->setHebergement(null);
            }
        }

        return $this;
    }
}