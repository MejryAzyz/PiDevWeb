<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Role;
use Doctrine\Common\Collections\Collection;
use App\Entity\Reservation_transport;

#[ORM\Entity]
class Utilisateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_utilisateur;

        #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: "utilisateurs")]
    #[ORM\JoinColumn(name: 'id_role', referencedColumnName: 'id_role', onDelete: 'CASCADE')]
    private Role $id_role;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "string", length: 255)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $mot_de_passe;

    #[ORM\Column(type: "string", length: 20)]
    private string $telephone;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_naissance;

    #[ORM\Column(type: "text")]
    private string $adresse;

    #[ORM\Column(type: "string", length: 255)]
    private string $image_url;

    #[ORM\Column(type: "integer")]
    private int $status;

    #[ORM\Column(type: "integer")]
    private int $verif;

    #[ORM\Column(type: "string", length: 255)]
    private string $verification_token;

    #[ORM\Column(type: "string", length: 100)]
    private string $nationalite;

    #[ORM\Column(type: "string", length: 255)]
    private string $reset_password_token;

    public function getId_utilisateur()
    {
        return $this->id_utilisateur;
    }

    public function setId_utilisateur($value)
    {
        $this->id_utilisateur = $value;
    }

    public function getId_role()
    {
        return $this->id_role;
    }

    public function setId_role($value)
    {
        $this->id_role = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($value)
    {
        $this->prenom = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getMot_de_passe()
    {
        return $this->mot_de_passe;
    }

    public function setMot_de_passe($value)
    {
        $this->mot_de_passe = $value;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($value)
    {
        $this->telephone = $value;
    }

    public function getDate_naissance()
    {
        return $this->date_naissance;
    }

    public function setDate_naissance($value)
    {
        $this->date_naissance = $value;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($value)
    {
        $this->adresse = $value;
    }

    public function getImage_url()
    {
        return $this->image_url;
    }

    public function setImage_url($value)
    {
        $this->image_url = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getVerif()
    {
        return $this->verif;
    }

    public function setVerif($value)
    {
        $this->verif = $value;
    }

    public function getVerification_token()
    {
        return $this->verification_token;
    }

    public function setVerification_token($value)
    {
        $this->verification_token = $value;
    }

    public function getNationalite()
    {
        return $this->nationalite;
    }

    public function setNationalite($value)
    {
        $this->nationalite = $value;
    }

    public function getReset_password_token()
    {
        return $this->reset_password_token;
    }

    public function setReset_password_token($value)
    {
        $this->reset_password_token = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_patient", targetEntity: Reservation_hebergement::class)]
    private Collection $reservation_hebergements;

        public function getReservation_hebergements(): Collection
        {
            return $this->reservation_hebergements;
        }
    
        public function addReservation_hebergement(Reservation_hebergement $reservation_hebergement): self
        {
            if (!$this->reservation_hebergements->contains($reservation_hebergement)) {
                $this->reservation_hebergements[] = $reservation_hebergement;
                $reservation_hebergement->setId_patient($this);
            }
    
            return $this;
        }
    
        public function removeReservation_hebergement(Reservation_hebergement $reservation_hebergement): self
        {
            if ($this->reservation_hebergements->removeElement($reservation_hebergement)) {
                // set the owning side to null (unless already changed)
                if ($reservation_hebergement->getId_patient() === $this) {
                    $reservation_hebergement->setId_patient(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_patient", targetEntity: Reservation_transport::class)]
    private Collection $reservation_transports;
}
