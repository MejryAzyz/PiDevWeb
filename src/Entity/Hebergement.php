<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\HebergementRepository;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
#[ORM\Table(name: 'hebergement')]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_hebergement = null;

    public function getId_hebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function setId_hebergement(int $id_hebergement): self
    {
        $this->id_hebergement = $id_hebergement;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $telephone = null;

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $capacite = null;

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $tarif_nuit = null;

    public function getTarif_nuit(): ?float
    {
        return $this->tarif_nuit;
    }

    public function setTarif_nuit(float $tarif_nuit): self
    {
        $this->tarif_nuit = $tarif_nuit;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $image_url = null;

    public function getImage_url(): ?string
    {
        return $this->image_url;
    }

    public function setImage_url(string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: HebergementPhoto::class, mappedBy: 'hebergement')]
    private Collection $hebergementPhotos;

    /**
     * @return Collection<int, HebergementPhoto>
     */
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

    #[ORM\OneToMany(targetEntity: ReservationHebergement::class, mappedBy: 'hebergement')]
    private Collection $reservationHebergements;

    public function __construct()
    {
        $this->hebergementPhotos = new ArrayCollection();
        $this->reservationHebergements = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReservationHebergement>
     */
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

    public function getIdHebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function getTarifNuit(): ?string
    {
        return $this->tarif_nuit;
    }

    public function setTarifNuit(string $tarif_nuit): static
    {
        $this->tarif_nuit = $tarif_nuit;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

}
