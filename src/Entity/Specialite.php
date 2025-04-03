<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\SpecialiteRepository;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
#[ORM\Table(name: 'specialite')]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_specialite = null;

    public function getId_specialite(): ?int
    {
        return $this->id_specialite;
    }

    public function setId_specialite(int $id_specialite): self
    {
        $this->id_specialite = $id_specialite;
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
