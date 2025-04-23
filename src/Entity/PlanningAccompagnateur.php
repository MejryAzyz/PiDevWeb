<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PlanningAccompagnateurRepository;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



#[ORM\Entity(repositoryClass: PlanningAccompagnateurRepository::class)]
#[ORM\Table(name: 'planning_accompagnateur')]
#[Assert\Callback('validateHeures')]
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
    #[Assert\NotBlank(message:"nom accompagnateur est obligatoire")]
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
    #[Assert\NotBlank(message:"La date du rendez-vous est obligatoire")]
    #[Assert\Type("\DateTimeInterface",message:"saisir une date valide")]
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
    #[Assert\NotBlank(message:"heure début est obligatoire")]
    #[Assert\Regex(
        pattern: "/^([01]\d|2[0-3]):[0-5]\d$/",
        message: "l'heure début doit etre sous la format HH:mm"
    )]
    
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
    #[Assert\NotBlank(message:"heure fin est obligatoire")]
    #[Assert\Regex(
        pattern: "/^([01]\d|2[0-3]):[0-5]\d$/",
        message: "l'heure fin doit etre sous la format HH:mm"
    )]
    #[Assert\NotBlank(message:"heure début est obligatoire")]
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

    public function validateHeures(ExecutionContextInterface $context): void
    {
        if (!$this->heure_debut || !$this->heure_fin) {
            return; 
        }

        $debut = \DateTime::createFromFormat('H:i', $this->heure_debut);
        $fin = \DateTime::createFromFormat('H:i', $this->heure_fin);

        if (!$debut || !$fin) {
            return; 
        }

        if ($fin <= $debut) {
            $context->buildViolation("L'heure de fin doit être après l'heure de début.")
                ->atPath('heure_fin')
                ->addViolation();
        }
    }

    #[ORM\ManyToOne(inversedBy: 'planningAccompagnateurs', targetEntity: DossierMedical::class)]
    #[ORM\JoinColumn(name: 'dossier_medical_id', referencedColumnName: 'id')]
    private ?DossierMedical $dossierMedical = null;

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): static
    {
        $this->dossierMedical = $dossierMedical;
        return $this;
    }
}
