<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PlanningDocteurRepository;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanningDocteurRepository::class)]
#[ORM\Table(name: 'planning_docteur')]
class PlanningDocteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_planning', type: 'integer')]
    private ?int $idPlanning = null;

    public function getIdPlanning(): ?int
    {
        return $this->idPlanning;
    }

    public function setIdPlanning(int $idPlanning): self
    {
        $this->idPlanning = $idPlanning;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Docteur::class, inversedBy: 'planningDocteurs')]
    #[ORM\JoinColumn(name: 'id_docteur', referencedColumnName: 'id_docteur')]
    #[Assert\NotBlank(message:"nom docteur est obligatoire")]
    private ?Docteur $docteur = null;

    public function getDocteur(): ?Docteur
    {
        return $this->docteur;
    }

    public function setDocteur(?Docteur $docteur): self
    {
        $this->docteur = $docteur;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotBlank(message:"La date du rendez-vous est obligatoire")]
    #[Assert\Type("\DateTimeInterface",message:"saisir une date valide")]
    private ?\DateTimeInterface $dateJour = null;

    public function getDateJour(): ?\DateTimeInterface
    {
        return $this->dateJour;
    }

    public function setDateJour(\DateTimeInterface $dateJour): self
    {
        $this->dateJour = $dateJour;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message:"heure début est obligatoire")]
    #[Assert\Regex(
        pattern: "/^([01]\d|2[0-3]):[0-5]\d$/",
        message: "l'heure début doit etre sous la format HH:mm"
    )]
    private ?string $heureDebut = null;

    public function getHeureDebut(): ?string
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(string $heureDebut): self
    {
        $this->heureDebut = $heureDebut;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message:"heure fin est obligatoire")]
    #[Assert\Regex(
        pattern: "/^([01]\d|2[0-3]):[0-5]\d$/",
        message: "l'heure fin doit etre sous la format HH:mm"
    )]
    private ?string $heureFin = null;

    public function getHeureFin(): ?string
    {
        return $this->heureFin;
    }

    public function setHeureFin(string $heureFin): self
    {
        $this->heureFin = $heureFin;
        return $this;
    }

    #[ORM\ManyToOne(inversedBy: 'planningDocteurs', targetEntity: DossierMedical::class)]
    #[ORM\JoinColumn(name: 'dossier_medical_id', referencedColumnName: 'id')]
    private ?DossierMedical $dossierMedical = null;

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): self
    {
        $this->dossierMedical = $dossierMedical;
        return $this;
    }
}
