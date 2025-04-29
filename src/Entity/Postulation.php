<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // ➔ Ajouté pour validation
use App\Entity\Offreemploi;

#[ORM\Entity]
class Postulation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id_postulation = null;

    #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: "postulations")]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur', onDelete: 'CASCADE')]
    private Accompagnateur $id_accompagnateur;

    #[ORM\ManyToOne(targetEntity: Offreemploi::class, inversedBy: "postulations")]
    #[ORM\JoinColumn(name: 'id_offre', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Offreemploi $id_offre;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotNull(message: "La date de postulation est obligatoire.")]
    private \DateTimeInterface $date_postulation;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(
        choices: ["Accepted", "Rejected", "Pending"],
        message: "Le statut doit être 'Accepted', 'Rejected' ou 'Pending'."
    )]
    private string $statut;

    public function getId_postulation(): ?int
    {
        return $this->id_postulation;
    }

    public function setId_postulation($value)
    {
        $this->id_postulation = $value;
    }

    public function getIdaccompagnateur()
    {
        return $this->id_accompagnateur;
    }

    public function setIdaccompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getIdoffre()
    {
        return $this->id_offre;
    }

    public function setIdoffre($value)
    {
        $this->id_offre = $value;
    }

    public function getDatepostulation()
    {
        return $this->date_postulation;
    }

    public function setDatepostulation($value)
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
