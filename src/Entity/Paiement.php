<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Reservation;

use App\Repository\PaiementRepository;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ORM\Table(name: 'paiement')]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_paiement = null;

    public function getId_paiement(): ?int
    {
        return $this->id_paiement;
    }

    public function setId_paiement(int $id_paiement): self
    {
        $this->id_paiement = $id_paiement;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            "Paiement ID: %d, Montant: %.2f, Date: %s, Méthode: %s",
            $this->id_paiement,
            $this->montant,
            $this->date_paiement->format('Y-m-d H:i:s'),
            $this->methode
        );
    }

    #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'paiments')]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id_reservation', nullable: false)]
    private ?Reservation $id_reservation = null;

    public function getId_reservation(): ?Reservation
    {
        return $this->id_reservation;
    }

    public function setId_reservation(?Reservation $reservation): self
    {
        $this->id_reservation = $reservation;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $montant = null;

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_paiement = null;

    public function getDate_paiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDate_paiement(\DateTimeInterface $date_paiement): self
    {
        $this->date_paiement = $date_paiement;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $methode = null;

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): self
    {
        $this->methode = $methode;
        return $this;
    }

    public function getIdPaiement(): ?int
    {
        return $this->id_paiement;
    }

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }



    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTimeInterface $date_paiement): static
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }
}
