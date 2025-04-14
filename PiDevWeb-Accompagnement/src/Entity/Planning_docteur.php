<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Docteur;

#[ORM\Entity]
class Planning_docteur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_planning;

        #[ORM\ManyToOne(targetEntity: Docteur::class, inversedBy: "planning_docteurs")]
    #[ORM\JoinColumn(name: 'id_docteur', referencedColumnName: 'id_docteur', onDelete: 'CASCADE')]
    private Docteur $id_docteur;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_jour;

    #[ORM\Column(type: "string", length: 255)]
    private string $heure_debut;

    #[ORM\Column(type: "string", length: 255)]
    private string $heure_fin;

    public function getId_planning()
    {
        return $this->id_planning;
    }

    public function setId_planning($value)
    {
        $this->id_planning = $value;
    }

    public function getId_docteur()
    {
        return $this->id_docteur;
    }

    public function setId_docteur($value)
    {
        $this->id_docteur = $value;
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
}
