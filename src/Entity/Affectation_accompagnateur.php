<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Reservation;

#[ORM\Entity]
class Affectation_accompagnateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_affectation;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_debut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_fin;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

        #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

        #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id_reservation', onDelete: 'CASCADE')]
    private Reservation $id_reservation;

    public function getId_affectation()
    {
        return $this->id_affectation;
    }

    public function setId_affectation($value)
    {
        $this->id_affectation = $value;
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

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getId_accompagnateur()
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getId_reservation()
    {
        return $this->id_reservation;
    }

    public function setId_reservation($value)
    {
        $this->id_reservation = $value;
    }
}
