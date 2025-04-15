<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TransportRepository;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\NotBlank(message: "Le type de transport est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le type doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le type ne peut pas dépasser {{ limit }} caractères."
    )]
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
    #[Assert\NotBlank(message: "La capacité est obligatoire.")]
    #[Assert\Positive(message: "La capacité doit être un nombre positif.")]
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

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "Le tarif est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le tarif doit être un nombre positif ou zéro.")]
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
