<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Hebergement;

#[ORM\Entity]
class Reservation_hebergement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_reservation_hebergement;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "reservation_hebergements")]
    #[ORM\JoinColumn(name: 'id_patient', referencedColumnName: 'id_utilisateur', onDelete: 'CASCADE')]
    private Utilisateur $id_patient;

        #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: "reservation_hebergements")]
    #[ORM\JoinColumn(name: 'id_hebergement', referencedColumnName: 'id_hebergement', onDelete: 'CASCADE')]
    private Hebergement $id_hebergement;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_debut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_fin;

    public function getId_reservation_hebergement()
    {
        return $this->id_reservation_hebergement;
    }

    public function setId_reservation_hebergement($value)
    {
        $this->id_reservation_hebergement = $value;
    }

    public function getId_patient()
    {
        return $this->id_patient;
    }

    public function setId_patient($value)
    {
        $this->id_patient = $value;
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
}
