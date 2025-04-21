<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_service')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_service', length: 255)]
    private ?string $nomService = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(name: 'id_hebergement', referencedColumnName: 'id_hebergement', nullable: false)]
    private ?Hebergement $hebergement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(string $nomService): static
    {
        $this->nomService = $nomService;
        return $this;
    }

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): static
    {
        $this->hebergement = $hebergement;
        return $this;
    }
} 