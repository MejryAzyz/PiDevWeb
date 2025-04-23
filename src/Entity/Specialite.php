<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<<<<<<< HEAD

use App\Repository\SpecialiteRepository;
=======
use App\Repository\SpecialiteRepository;
use Symfony\Component\Validator\Constraints as Assert;
>>>>>>> c4098f6 (bundle)

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
#[ORM\Table(name: 'specialite')]
class Specialite
{
    #[ORM\Id]
<<<<<<< HEAD
    #[ORM\GeneratedValue]
=======
    #[ORM\GeneratedValue(strategy: "AUTO")]
>>>>>>> c4098f6 (bundle)
    #[ORM\Column(type: 'integer')]
    private ?int $id_specialite = null;

    public function getId_specialite(): ?int
    {
        return $this->id_specialite;
    }

<<<<<<< HEAD
    public function setId_specialite(int $id_specialite): self
    {
        $this->id_specialite = $id_specialite;
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

    #[ORM\OneToMany(targetEntity: Docteur::class, mappedBy: 'specialite')]
    private Collection $docteurs;

    public function __construct()
    {
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

    public function getIdSpecialite(): ?int
    {
        return $this->id_specialite;
    }

}
