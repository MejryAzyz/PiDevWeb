<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Docteur;

#[ORM\Entity]
class Specialite
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_specialite;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    public function getId_specialite()
    {
        return $this->id_specialite;
    }

    public function setId_specialite($value)
    {
        $this->id_specialite = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_specialite", targetEntity: Docteur::class)]
    private Collection $docteurs;

        public function getDocteurs(): Collection
        {
            return $this->docteurs;
        }
    
        public function addDocteur(Docteur $docteur): self
        {
            if (!$this->docteurs->contains($docteur)) {
                $this->docteurs[] = $docteur;
                $docteur->setId_specialite($this);
            }
    
            return $this;
        }
    
        public function removeDocteur(Docteur $docteur): self
        {
            if ($this->docteurs->removeElement($docteur)) {
                // set the owning side to null (unless already changed)
                if ($docteur->getId_specialite() === $this) {
                    $docteur->setId_specialite(null);
                }
            }
    
            return $this;
        }
}
