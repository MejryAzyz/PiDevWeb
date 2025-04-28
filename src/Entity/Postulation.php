<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offreemploi;

#[ORM\Entity]
class Postulation
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id_postulation;

        #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "postulations")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

        #[ORM\ManyToOne(targetEntity: Offreemploi::class, inversedBy: "postulations")]
    #[ORM\JoinColumn(name: 'id_offre', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Offreemploi $id_offre;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_postulation;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    public function getId_postulation()
    {
        return $this->id_postulation;
    }

    public function setId_postulation($value)
    {
        $this->id_postulation = $value;
    }

    public function getId_accompagnateur()
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getId_offre()
    {
        return $this->id_offre;
    }

    public function setId_offre($value)
    {
        $this->id_offre = $value;
    }

    public function getDate_postulation()
    {
        return $this->date_postulation;
    }

    public function setDate_postulation($value)
    {
        $this->date_postulation = $value;
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
