<?php

namespace App\Entity;

<<<<<<< HEAD
use Doctrine\DBAL\Types\Types;
=======
>>>>>>> c4098f6 (bundle)
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Reservation;

#[ORM\Entity]
class Affectation_accompagnateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_affectation;

<<<<<<< HEAD
        #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

        #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id_reservation', onDelete: 'CASCADE')]
    private Reservation $id_reservation;

=======
>>>>>>> c4098f6 (bundle)
    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_debut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_fin;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

<<<<<<< HEAD
=======
        #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

        #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: "affectation_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id_reservation', onDelete: 'CASCADE')]
    private Reservation $id_reservation;

>>>>>>> c4098f6 (bundle)
    public function getId_affectation()
    {
        return $this->id_affectation;
    }

    public function setId_affectation($value)
    {
        $this->id_affectation = $value;
    }

<<<<<<< HEAD
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

=======
>>>>>>> c4098f6 (bundle)
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

<<<<<<< HEAD
    public function getIdAffectation(): ?int
    {
        return $this->id_affectation;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getIdAccompagnateur(): ?Accompagnateur
=======
    public function getId_accompagnateur()
>>>>>>> c4098f6 (bundle)
    {
        return $this->id_accompagnateur;
    }

<<<<<<< HEAD
    public function setIdAccompagnateur(?Accompagnateur $id_accompagnateur): static
    {
        $this->id_accompagnateur = $id_accompagnateur;

        return $this;
    }

    public function getIdReservation(): ?Reservation
=======
    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getId_reservation()
>>>>>>> c4098f6 (bundle)
    {
        return $this->id_reservation;
    }

<<<<<<< HEAD
    public function setIdReservation(?Reservation $id_reservation): static
    {
        $this->id_reservation = $id_reservation;

        return $this;
=======
    public function setId_reservation($value)
    {
        $this->id_reservation = $value;
>>>>>>> c4098f6 (bundle)
    }
}
