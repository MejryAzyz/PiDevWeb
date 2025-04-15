<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'service')]
    #[ORM\JoinColumn(name: 'id_hebergement', referencedColumnName: 'id_hebergement', nullable: false)]
    private ?Hebergement $hebergement = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $wifi = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $climatisation = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $menage_quotidien = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $conciergerie = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $linge_lit = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $salle_bain_privee = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): self
    {
        $this->hebergement = $hebergement;
        return $this;
    }

    public function isWifi(): bool
    {
        return $this->wifi;
    }

    public function setWifi(bool $wifi): self
    {
        $this->wifi = $wifi;
        return $this;
    }

    public function isClimatisation(): bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(bool $climatisation): self
    {
        $this->climatisation = $climatisation;
        return $this;
    }

    public function isMenageQuotidien(): bool
    {
        return $this->menage_quotidien;
    }

    public function setMenageQuotidien(bool $menage_quotidien): self
    {
        $this->menage_quotidien = $menage_quotidien;
        return $this;
    }

    public function isConciergerie(): bool
    {
        return $this->conciergerie;
    }

    public function setConciergerie(bool $conciergerie): self
    {
        $this->conciergerie = $conciergerie;
        return $this;
    }

    public function isLingeLit(): bool
    {
        return $this->linge_lit;
    }

    public function setLingeLit(bool $linge_lit): self
    {
        $this->linge_lit = $linge_lit;
        return $this;
    }

    public function isSalleBainPrivee(): bool
    {
        return $this->salle_bain_privee;
    }

    public function setSalleBainPrivee(bool $salle_bain_privee): self
    {
        $this->salle_bain_privee = $salle_bain_privee;
        return $this;
    }
} 