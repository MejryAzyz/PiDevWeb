<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Reservation_hebergement;

#[ORM\Entity]
class Hebergement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_hebergement;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "text")]
    private string $adresse;

    #[ORM\Column(type: "string", length: 20)]
    private string $telephone;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "integer")]
    private int $capacite;

    #[ORM\Column(type: "float")]
    private float $tarif_nuit;

    #[ORM\Column(type: "string", length: 255)]
    private string $image_url;

    public function getId_hebergement()
    {
        return $this->id_hebergement;
    }

    public function setId_hebergement($value)
    {
        $this->id_hebergement = $value;
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

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function setCapacite($value)
    {
        $this->capacite = $value;
    }

    public function getTarif_nuit()
    {
        return $this->tarif_nuit;
    }

    public function setTarif_nuit($value)
    {
        $this->tarif_nuit = $value;
    }

    public function getImage_url()
    {
        return $this->image_url;
    }

    public function setImage_url($value)
    {
        $this->image_url = $value;
    }

    #[ORM\OneToMany(mappedBy: "hebergement_id", targetEntity: Hebergement_photos::class)]
    private Collection $hebergement_photoss;

        public function getHebergement_photoss(): Collection
        {
            return $this->hebergement_photoss;
        }
    
        public function addHebergement_photos(Hebergement_photos $hebergement_photos): self
        {
            if (!$this->hebergement_photoss->contains($hebergement_photos)) {
                $this->hebergement_photoss[] = $hebergement_photos;
                $hebergement_photos->setHebergement_id($this);
            }
    
            return $this;
        }
    
        public function removeHebergement_photos(Hebergement_photos $hebergement_photos): self
        {
            if ($this->hebergement_photoss->removeElement($hebergement_photos)) {
                // set the owning side to null (unless already changed)
                if ($hebergement_photos->getHebergement_id() === $this) {
                    $hebergement_photos->setHebergement_id(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_hebergement", targetEntity: Reservation_hebergement::class)]
    private Collection $reservation_hebergements;

        public function getReservation_hebergements(): Collection
        {
            return $this->reservation_hebergements;
        }
    
        public function addReservation_hebergement(Reservation_hebergement $reservation_hebergement): self
        {
            if (!$this->reservation_hebergements->contains($reservation_hebergement)) {
                $this->reservation_hebergements[] = $reservation_hebergement;
                $reservation_hebergement->setId_hebergement($this);
            }
    
            return $this;
        }
    
        public function removeReservation_hebergement(Reservation_hebergement $reservation_hebergement): self
        {
            if ($this->reservation_hebergements->removeElement($reservation_hebergement)) {
                // set the owning side to null (unless already changed)
                if ($reservation_hebergement->getId_hebergement() === $this) {
                    $reservation_hebergement->setId_hebergement(null);
                }
            }
    
            return $this;
        }
}
