<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Postulation;

#[ORM\Entity]
class Accompagnateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_accompagnateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password_hash;

    #[ORM\Column(type: "string", length: 150)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $fichier_cv;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_profil;

    #[ORM\Column(type: "text")]
    private string $experience;

    #[ORM\Column(type: "text")]
    private string $motivation;

    #[ORM\Column(type: "string", length: 255)]
    private string $langues;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_recrutement;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_inscription;

    public function getId_accompagnateur()
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword_hash()
    {
        return $this->password_hash;
    }

    public function setPassword_hash($value)
    {
        $this->password_hash = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getFichier_cv()
    {
        return $this->fichier_cv;
    }

    public function setFichier_cv($value)
    {
        $this->fichier_cv = $value;
    }

    public function getPhoto_profil()
    {
        return $this->photo_profil;
    }

    public function setPhoto_profil($value)
    {
        $this->photo_profil = $value;
    }

    public function getExperience()
    {
        return $this->experience;
    }

    public function setExperience($value)
    {
        $this->experience = $value;
    }

    public function getMotivation()
    {
        return $this->motivation;
    }

    public function setMotivation($value)
    {
        $this->motivation = $value;
    }

    public function getLangues()
    {
        return $this->langues;
    }

    public function setLangues($value)
    {
        $this->langues = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getDate_recrutement()
    {
        return $this->date_recrutement;
    }

    public function setDate_recrutement($value)
    {
        $this->date_recrutement = $value;
    }

    public function getDate_inscription()
    {
        return $this->date_inscription;
    }

    public function setDate_inscription($value)
    {
        $this->date_inscription = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Planning_accompagnateur::class)]
    private Collection $planning_accompagnateurs;

        public function getPlanning_accompagnateurs(): Collection
        {
            return $this->planning_accompagnateurs;
        }
    
        public function addPlanning_accompagnateur(Planning_accompagnateur $planning_accompagnateur): self
        {
            if (!$this->planning_accompagnateurs->contains($planning_accompagnateur)) {
                $this->planning_accompagnateurs[] = $planning_accompagnateur;
                $planning_accompagnateur->setId_accompagnateur($this);
            }
    
            return $this;
        }
    
        public function removePlanning_accompagnateur(Planning_accompagnateur $planning_accompagnateur): self
        {
            if ($this->planning_accompagnateurs->removeElement($planning_accompagnateur)) {
                // set the owning side to null (unless already changed)
                if ($planning_accompagnateur->getId_accompagnateur() === $this) {
                    $planning_accompagnateur->setId_accompagnateur(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Affectation_accompagnateur::class)]
    private Collection $affectation_accompagnateurs;

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Postulation::class)]
    private Collection $postulations;
}
