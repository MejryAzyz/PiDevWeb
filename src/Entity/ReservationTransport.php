<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationTransportRepository;

#[ORM\Entity(repositoryClass: ReservationTransportRepository::class)]
#[ORM\Table(name: 'reservation_transport')]
class ReservationTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_reservation_transport = null;

    public function getId_reservation_transport(): ?int
    {
        return $this->id_reservation_transport;
    }

    public function setId_reservation_transport(int $id_reservation_transport): self
    {
        $this->id_reservation_transport = $id_reservation_transport;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'reservationTransports')]
    #[ORM\JoinColumn(name: 'id_patient', referencedColumnName: 'id_utilisateur')]
    private ?Utilisateur $utilisateur = null;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Transport::class, inversedBy: 'reservationTransports')]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id_transport')]
    private ?Transport $transport = null;

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(?Transport $transport): self
    {
        $this->transport = $transport;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $heure_depart = null;

    public function getHeure_depart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeure_depart(string $heure_depart): self
    {
        $this->heure_depart = $heure_depart;
        return $this;
    }

    public function getIdReservationTransport(): ?int
    {
        return $this->id_reservation_transport;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeureDepart(string $heure_depart): static
    {
        $this->heure_depart = $heure_depart;

        return $this;
    }

}
