<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Docteur;

#[ORM\Entity]
class Clinique
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_clinique;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "text")]
    private string $adresse;

    #[ORM\Column(type: "string", length: 20)]
    private string $telephone;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "integer")]
    private int $rate;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "float")]
    private float $prix;

    public function getId_clinique()
    {
        return $this->id_clinique;
    }

    public function setId_clinique($value)
    {
        $this->id_clinique = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($value)
    {
        $this->adresse = $value;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($value)
    {
        $this->telephone = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($value)
    {
        $this->rate = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($value)
    {
        $this->prix = $value;
    }

    #[ORM\OneToMany(mappedBy: "clinique_id", targetEntity: Clinique_photos::class)]
    private Collection $clinique_photoss;

        public function getClinique_photoss(): Collection
        {
            return $this->clinique_photoss;
        }
    
        public function addClinique_photos(Clinique_photos $clinique_photos): self
        {
            if (!$this->clinique_photoss->contains($clinique_photos)) {
                $this->clinique_photoss[] = $clinique_photos;
                $clinique_photos->setClinique_id($this);
            }
    
            return $this;
        }
    
        public function removeClinique_photos(Clinique_photos $clinique_photos): self
        {
            if ($this->clinique_photoss->removeElement($clinique_photos)) {
                // set the owning side to null (unless already changed)
                if ($clinique_photos->getClinique_id() === $this) {
                    $clinique_photos->setClinique_id(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_clinique", targetEntity: Docteur::class)]
    private Collection $docteurs;

        public function getDocteurs(): Collection
        {
            return $this->docteurs;
        }
    
        public function addDocteur(Docteur $docteur): self
        {
            if (!$this->docteurs->contains($docteur)) {
                $this->docteurs[] = $docteur;
                $docteur->setId_clinique($this);
            }
    
            return $this;
        }
    
        public function removeDocteur(Docteur $docteur): self
        {
            if ($this->docteurs->removeElement($docteur)) {
                // set the owning side to null (unless already changed)
                if ($docteur->getId_clinique() === $this) {
                    $docteur->setId_clinique(null);
                }
            }
    
            return $this;
        }
}
