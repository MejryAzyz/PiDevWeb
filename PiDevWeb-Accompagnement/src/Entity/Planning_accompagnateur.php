<?php

namespace App\Entity;

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

    #[ORM\Column(type: "string", length: 255)]
    private string $heure_debut;

    #[ORM\Column(type: "string", length: 255)]
    private string $heure_fin;

    #[ORM\Column(type: "string", length: 255)]
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
}
