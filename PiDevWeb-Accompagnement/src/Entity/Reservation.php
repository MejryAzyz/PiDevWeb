<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Affectation_accompagnateur;

#[ORM\Entity]
class Reservation
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_reservation;

    #[ORM\Column(type: "integer")]
    private int $id_patient;

    #[ORM\Column(type: "integer")]
    private int $id_clinique;

    #[ORM\Column(type: "integer")]
    private int $id_transport;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_depart;

    #[ORM\Column(type: "string", length: 255)]
    private string $heure_depart;

    #[ORM\Column(type: "integer")]
    private int $id_hebergement;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_debut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_fin;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_reservation;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    public function getId_reservation()
    {
        return $this->id_reservation;
    }

    public function setId_reservation($value)
    {
        $this->id_reservation = $value;
    }

    public function getId_patient()
    {
        return $this->id_patient;
    }

    public function setId_patient($value)
    {
        $this->id_patient = $value;
    }

    public function getId_clinique()
    {
        return $this->id_clinique;
    }

    public function setId_clinique($value)
    {
        $this->id_clinique = $value;
    }

    public function getId_transport()
    {
        return $this->id_transport;
    }

    public function setId_transport($value)
    {
        $this->id_transport = $value;
    }

    public function getDate_depart()
    {
        return $this->date_depart;
    }

    public function setDate_depart($value)
    {
        $this->date_depart = $value;
    }

    public function getHeure_depart()
    {
        return $this->heure_depart;
    }

    public function setHeure_depart($value)
    {
        $this->heure_depart = $value;
    }

    public function getId_hebergement()
    {
        return $this->id_hebergement;
    }

    public function setId_hebergement($value)
    {
        $this->id_hebergement = $value;
    }

    public function getDate_debut()
    {
        return $this->date_debut;
    }

    public function setDate_debut($value)
    {
        $this->date_debut = $value;
    }

    public function getDate_fin()
    {
        return $this->date_fin;
    }

    public function setDate_fin($value)
    {
        $this->date_fin = $value;
    }

    public function getDate_reservation()
    {
        return $this->date_reservation;
    }

    public function setDate_reservation($value)
    {
        $this->date_reservation = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_reservation", targetEntity: Affectation_accompagnateur::class)]
    private Collection $affectation_accompagnateurs;

        public function getAffectation_accompagnateurs(): Collection
        {
            return $this->affectation_accompagnateurs;
        }
    
        public function addAffectation_accompagnateur(Affectation_accompagnateur $affectation_accompagnateur): self
        {
            if (!$this->affectation_accompagnateurs->contains($affectation_accompagnateur)) {
                $this->affectation_accompagnateurs[] = $affectation_accompagnateur;
                $affectation_accompagnateur->setId_reservation($this);
            }
    
            return $this;
        }
    
        public function removeAffectation_accompagnateur(Affectation_accompagnateur $affectation_accompagnateur): self
        {
            if ($this->affectation_accompagnateurs->removeElement($affectation_accompagnateur)) {
                // set the owning side to null (unless already changed)
                if ($affectation_accompagnateur->getId_reservation() === $this) {
                    $affectation_accompagnateur->setId_reservation(null);
                }
            }
    
            return $this;
        }
}
