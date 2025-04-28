<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Transport;

#[ORM\Entity]
class Reservation_transport
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_reservation_transport;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "reservation_transports")]
    #[ORM\JoinColumn(name: 'id_patient', referencedColumnName: 'id_utilisateur', onDelete: 'CASCADE')]
    private Utilisateur $id_patient;

        #[ORM\ManyToOne(targetEntity: Transport::class, inversedBy: "reservation_transports")]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id_transport', onDelete: 'CASCADE')]
    private Transport $id_transport;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_depart;

    #[ORM\Column(type: "string")]
    private string $heure_depart;

    public function getId_reservation_transport()
    {
        return $this->id_reservation_transport;
    }

    public function setId_reservation_transport($value)
    {
        $this->id_reservation_transport = $value;
    }

    public function getId_patient()
    {
        return $this->id_patient;
    }

    public function setId_patient($value)
    {
        $this->id_patient = $value;
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

    public function getIdPatient(): ?Utilisateur
    {
        return $this->id_patient;
    }

    public function setIdPatient(?Utilisateur $id_patient): static
    {
        $this->id_patient = $id_patient;

        return $this;
    }

    public function getIdTransport(): ?Transport
    {
        return $this->id_transport;
    }

    public function setIdTransport(?Transport $id_transport): static
    {
        $this->id_transport = $id_transport;

        return $this;
    }
}
