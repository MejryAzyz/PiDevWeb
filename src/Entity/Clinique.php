<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CliniqueRepository;

#[ORM\Entity(repositoryClass: CliniqueRepository::class)]
#[ORM\Table(name: 'clinique')]
class Clinique
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id_clinique = null;

    public function getId_clinique(): ?int
    {
        return $this->id_clinique;
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
    private ?int $rate = 0;

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $prix = null;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: CliniquePhoto::class, mappedBy: 'clinique')]
    private Collection $cliniquePhotos;

    /**
     * @return Collection<int, CliniquePhoto>
     */
    public function getCliniquePhotos(): Collection
    {
        if (!$this->cliniquePhotos instanceof Collection) {
            $this->cliniquePhotos = new ArrayCollection();
        }
        return $this->cliniquePhotos;
    }

    public function addCliniquePhoto(CliniquePhoto $cliniquePhoto): self
    {
        if (!$this->getCliniquePhotos()->contains($cliniquePhoto)) {
            $this->getCliniquePhotos()->add($cliniquePhoto);
        }
        return $this;
    }

    public function removeCliniquePhoto(CliniquePhoto $cliniquePhoto): self
    {
        $this->getCliniquePhotos()->removeElement($cliniquePhoto);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Docteur::class, mappedBy: 'clinique')]
    private Collection $docteurs;

    public function __construct()
    {
        $this->cliniquePhotos = new ArrayCollection();
        $this->docteurs = new ArrayCollection();
    }

    /**
     * @return Collection<int, Docteur>
     */
    public function getDocteurs(): Collection
    {
        if (!$this->docteurs instanceof Collection) {
            $this->docteurs = new ArrayCollection();
        }
        return $this->docteurs;
    }

    public function addDocteur(Docteur $docteur): self
    {
        if (!$this->getDocteurs()->contains($docteur)) {
            $this->getDocteurs()->add($docteur);
        }
        return $this;
    }

    public function removeDocteur(Docteur $docteur): self
    {
        $this->getDocteurs()->removeElement($docteur);
        return $this;
    }

    public function getIdClinique(): ?int
    {
        return $this->id_clinique;
    }

}
