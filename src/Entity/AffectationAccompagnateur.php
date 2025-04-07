<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AffectationAccompagnateurRepository;

#[ORM\Entity(repositoryClass: AffectationAccompagnateurRepository::class)]
#[ORM\Table(name: 'affectation_accompagnateur')]
class AffectationAccompagnateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_affectation = null;

    public function getId_affectation(): ?int
    {
        return $this->id_affectation;
    }

    public function setId_affectation(int $id_affectation): self
    {
        $this->id_affectation = $id_affectation;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: 'affectationAccompagnateurs')]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur')]
    private ?Accompagnateur $accompagnateur = null;

    public function getAccompagnateur(): ?Accompagnateur
    {
        return $this->accompagnateur;
    }

    public function setAccompagnateur(?Accompagnateur $accompagnateur): self
    {
        $this->accompagnateur = $accompagnateur;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'affectationAccompagnateurs')]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id_reservation')]
    private ?Reservation $reservation = null;

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_debut = null;

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_fin = null;

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

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

}
