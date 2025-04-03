<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Accompagnateur;

#[ORM\Entity]
class Planning_accompagnateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_planning;

        #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "planning_accompagnateurs")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_jour;

    #[ORM\Column(type: "string")]
    private string $heure_debut;

    #[ORM\Column(type: "string")]
    private string $heure_fin;

    #[ORM\Column(type: "string")]
    private string $statut;

    public function getId_planning()
    {
        return $this->id_planning;
    }

    public function setId_planning($value)
    {
        $this->id_planning = $value;
    }

    public function getId_accompagnateur()
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getDate_jour()
    {
        return $this->date_jour;
    }

    public function setDate_jour($value)
    {
        $this->date_jour = $value;
    }

    public function getHeure_debut()
    {
        return $this->heure_debut;
    }

    public function setHeure_debut($value)
    {
        $this->heure_debut = $value;
    }

    public function getHeure_fin()
    {
        return $this->heure_fin;
    }

    public function setHeure_fin($value)
    {
        $this->heure_fin = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
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

    public function getIdAccompagnateur(): ?Accompagnateur
    {
        return $this->id_accompagnateur;
    }

    public function setIdAccompagnateur(?Accompagnateur $id_accompagnateur): static
    {
        $this->id_accompagnateur = $id_accompagnateur;

        return $this;
    }
}
