<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PlanningAccompagnateurRepository;

#[ORM\Entity(repositoryClass: PlanningAccompagnateurRepository::class)]
#[ORM\Table(name: 'planning_accompagnateur')]
class PlanningAccompagnateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_planning = null;

    public function getId_planning(): ?int
    {
        return $this->id_planning;
    }

    public function setId_planning(int $id_planning): self
    {
        $this->id_planning = $id_planning;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: 'planningAccompagnateurs')]
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

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_jour = null;

    public function getDate_jour(): ?\DateTimeInterface
    {
        return $this->date_jour;
    }

    public function setDate_jour(\DateTimeInterface $date_jour): self
    {
        $this->date_jour = $date_jour;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $heure_debut = null;

    public function getHeure_debut(): ?string
    {
        return $this->heure_debut;
    }

    public function setHeure_debut(string $heure_debut): self
    {
        $this->heure_debut = $heure_debut;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $heure_fin = null;

    public function getHeure_fin(): ?string
    {
        return $this->heure_fin;
    }

    public function setHeure_fin(string $heure_fin): self
    {
        $this->heure_fin = $heure_fin;
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

    public function getIdPlanning(): ?int
    {
        return $this->id_planning;
    }

    public function getDateJour(): ?\DateTimeInterface
    {
        return $this->date_jour;
    }

    public function setDateJour(\DateTimeInterface $date_jour): static
    {
        $this->date_jour = $date_jour;

        return $this;
    }

    public function getHeureDebut(): ?string
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(string $heure_debut): static
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heure_fin;
    }

    public function setHeureFin(string $heure_fin): static
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

}
