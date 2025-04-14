<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Reservation_transport;

#[ORM\Entity]
class Transport
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_transport;

    #[ORM\Column(type: "string", length: 255)]
    private string $type;

    #[ORM\Column(type: "integer")]
    private int $capacite;

    #[ORM\Column(type: "float")]
    private float $tarif;

    public function getId_transport()
    {
        return $this->id_transport;
    }

    public function setId_transport($value)
    {
        $this->id_transport = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function setCapacite($value)
    {
        $this->capacite = $value;
    }

    public function getTarif()
    {
        return $this->tarif;
    }

    public function setTarif($value)
    {
        $this->tarif = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_transport", targetEntity: Reservation_transport::class)]
    private Collection $reservation_transports;

        public function getReservation_transports(): Collection
        {
            return $this->reservation_transports;
        }
    
        public function addReservation_transport(Reservation_transport $reservation_transport): self
        {
            if (!$this->reservation_transports->contains($reservation_transport)) {
                $this->reservation_transports[] = $reservation_transport;
                $reservation_transport->setId_transport($this);
            }
    
            return $this;
        }
    
        public function removeReservation_transport(Reservation_transport $reservation_transport): self
        {
            if ($this->reservation_transports->removeElement($reservation_transport)) {
                // set the owning side to null (unless already changed)
                if ($reservation_transport->getId_transport() === $this) {
                    $reservation_transport->setId_transport(null);
                }
            }
    
            return $this;
        }
}
