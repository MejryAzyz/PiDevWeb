<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<<<<<<< HEAD

use App\Repository\CliniqueRepository;
=======
use App\Repository\CliniqueRepository;
use Symfony\Component\Validator\Constraints as Assert;
>>>>>>> c4098f6 (bundle)

#[ORM\Entity(repositoryClass: CliniqueRepository::class)]
#[ORM\Table(name: 'clinique')]
class Clinique
{
    #[ORM\Id]
<<<<<<< HEAD
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
=======
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
>>>>>>> c4098f6 (bundle)
    private ?int $id_clinique = null;

    public function getId_clinique(): ?int
    {
        return $this->id_clinique;
    }

<<<<<<< HEAD
    public function setId_clinique(int $id_clinique): self
    {
        $this->id_clinique = $id_clinique;
        return $this;
    }
=======
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
>>>>>>> c4098f6 (bundle)

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

<<<<<<< HEAD
=======
    // #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    // #[Assert\Length(
    //     min: 10,
    //     minMessage: "L'adresse doit contenir au moins {{ limit }} caractères."
    // )]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ0-9\s'-]+, [a-zA-ZÀ-ÿ0-9\s'-]+, [0-9]{5}$/",
        message: "L'adresse doit être au format 'Rue, Ville, Code Postal' (ex. : Rue de la Paix, Paris, 75001)"
    )]

>>>>>>> c4098f6 (bundle)
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

<<<<<<< HEAD
=======
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
     #[Assert\Regex(
    pattern: "/^\+?[0-9]{8,15}$/",
    message: "Le numéro de téléphone doit être composé de 8 à 15 chiffres, avec un '+' facultatif au début."
    )]

>>>>>>> c4098f6 (bundle)
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

<<<<<<< HEAD
=======
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    
>>>>>>> c4098f6 (bundle)
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
<<<<<<< HEAD
    private ?int $rate = null;
=======
    private ?int $rate = 0;
>>>>>>> c4098f6 (bundle)

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

<<<<<<< HEAD
=======
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        minMessage: "La description doit contenir au moins {{ limit }} caractères."
    )]
>>>>>>> c4098f6 (bundle)
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
<<<<<<< HEAD

=======
 
    #[Assert\NotBlank(message: "Le prix est obligatoire.")]
    #[Assert\Positive(message: "Le prix doit être un nombre positif.")]
>>>>>>> c4098f6 (bundle)
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

<<<<<<< HEAD
    #[ORM\OneToMany(targetEntity: CliniquePhoto::class, mappedBy: 'clinique')]
    private Collection $cliniquePhotos;

    /**
     * @return Collection<int, CliniquePhoto>
=======
    #[ORM\OneToMany(targetEntity: Clinique_photos::class, mappedBy: 'clinique_id')]
    private Collection $cliniquePhotos;

    /**
     * @return Collection<int, Clinique_photos>
>>>>>>> c4098f6 (bundle)
     */
    public function getCliniquePhotos(): Collection
    {
        if (!$this->cliniquePhotos instanceof Collection) {
            $this->cliniquePhotos = new ArrayCollection();
        }
        return $this->cliniquePhotos;
    }

<<<<<<< HEAD
    public function addCliniquePhoto(CliniquePhoto $cliniquePhoto): self
    {
        if (!$this->getCliniquePhotos()->contains($cliniquePhoto)) {
            $this->getCliniquePhotos()->add($cliniquePhoto);
=======
    public function addCliniquePhoto(Clinique_photos $cliniquePhoto): self
    {
        if (!$this->getCliniquePhotos()->contains($cliniquePhoto)) {
            $this->getCliniquePhotos()->add($cliniquePhoto);
            $cliniquePhoto->setCliniqueId($this);
>>>>>>> c4098f6 (bundle)
        }
        return $this;
    }

<<<<<<< HEAD
    public function removeCliniquePhoto(CliniquePhoto $cliniquePhoto): self
    {
        $this->getCliniquePhotos()->removeElement($cliniquePhoto);
=======
    public function removeCliniquePhoto(Clinique_photos $cliniquePhoto): self
    {
        if ($this->getCliniquePhotos()->contains($cliniquePhoto)) {
            $this->getCliniquePhotos()->removeElement($cliniquePhoto);
            // Set the clinique_id to null if it's this clinique
            if ($cliniquePhoto->getCliniqueId() === $this) {
                $cliniquePhoto->setCliniqueId(null);
            }
        }
>>>>>>> c4098f6 (bundle)
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
