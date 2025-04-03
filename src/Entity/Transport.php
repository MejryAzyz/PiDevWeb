<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TransportRepository;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name: 'transport')]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_transport = null;

    public function getId_transport(): ?int
    {
        return $this->id_transport;
    }

    public function setId_transport(int $id_transport): self
    {
        $this->id_transport = $id_transport;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $capacite = null;

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $tarif = null;

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReservationTransport::class, mappedBy: 'transport')]
    private Collection $reservationTransports;

    public function __construct()
    {
        $this->reservationTransports = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReservationTransport>
     */
    public function getReservationTransports(): Collection
    {
        if (!$this->reservationTransports instanceof Collection) {
            $this->reservationTransports = new ArrayCollection();
        }
        return $this->reservationTransports;
    }

    public function addReservationTransport(ReservationTransport $reservationTransport): self
    {
        if (!$this->getReservationTransports()->contains($reservationTransport)) {
            $this->getReservationTransports()->add($reservationTransport);
        }
        return $this;
    }

    public function removeReservationTransport(ReservationTransport $reservationTransport): self
    {
        $this->getReservationTransports()->removeElement($reservationTransport);
        return $this;
    }

    public function getIdTransport(): ?int
    {
        return $this->id_transport;
    }

}
