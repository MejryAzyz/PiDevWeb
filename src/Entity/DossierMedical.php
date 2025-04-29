<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
#[ORM\Table(name: 'dossier_medical')]
class DossierMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du patient est obligatoire")]
    private ?string $nomPatient = null;

    #[ORM\Column(length: 255)]
    private ?string $testsMedicaux = null;

    #[ORM\Column(length: 255)]
    private ?string $antecedents = null;

    #[ORM\Column(length: 255)]
    private ?string $allergies = null;

    #[ORM\Column(length: 255)]
    private ?string $restrictionsAlimentaires = null;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: PlanningDocteur::class)]
    private Collection $planningDocteurs;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: PlanningAccompagnateur::class)]
    private Collection $planningAccompagnateurs;

    public function __construct()
    {
        $this->planningDocteurs = new ArrayCollection();
        $this->planningAccompagnateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPatient(): ?string
    {
        return $this->nomPatient;
    }

    public function setNomPatient(string $nomPatient): static
    {
        $this->nomPatient = $nomPatient;
        return $this;
    }

    public function getTestsMedicaux(): ?string
    {
        return $this->testsMedicaux;
    }

    public function setTestsMedicaux(string $testsMedicaux): static
    {
        $this->testsMedicaux = $testsMedicaux;
        return $this;
    }

    public function getAntecedents(): ?string
    {
        return $this->antecedents;
    }

    public function setAntecedents(string $antecedents): static
    {
        $this->antecedents = $antecedents;
        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(string $allergies): static
    {
        $this->allergies = $allergies;
        return $this;
    }

    public function getRestrictionsAlimentaires(): ?string
    {
        return $this->restrictionsAlimentaires;
    }

    public function setRestrictionsAlimentaires(string $restrictionsAlimentaires): static
    {
        $this->restrictionsAlimentaires = $restrictionsAlimentaires;
        return $this;
    }

    /**
     * @return Collection<int, PlanningDocteur>
     */
    public function getPlanningDocteurs(): Collection
    {
        return $this->planningDocteurs;
    }

    public function addPlanningDocteur(PlanningDocteur $planningDocteur): static
    {
        if (!$this->planningDocteurs->contains($planningDocteur)) {
            $this->planningDocteurs->add($planningDocteur);
            $planningDocteur->setDossierMedical($this);
        }

        return $this;
    }

    public function removePlanningDocteur(PlanningDocteur $planningDocteur): static
    {
        if ($this->planningDocteurs->removeElement($planningDocteur)) {
            // set the owning side to null (unless already changed)
            if ($planningDocteur->getDossierMedical() === $this) {
                $planningDocteur->setDossierMedical(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlanningAccompagnateur>
     */
    public function getPlanningAccompagnateurs(): Collection
    {
        return $this->planningAccompagnateurs;
    }

    public function addPlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): static
    {
        if (!$this->planningAccompagnateurs->contains($planningAccompagnateur)) {
            $this->planningAccompagnateurs->add($planningAccompagnateur);
            $planningAccompagnateur->setDossierMedical($this);
        }

        return $this;
    }

    public function removePlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): static
    {
        if ($this->planningAccompagnateurs->removeElement($planningAccompagnateur)) {
            // set the owning side to null (unless already changed)
            if ($planningAccompagnateur->getDossierMedical() === $this) {
                $planningAccompagnateur->setDossierMedical(null);
            }
        }

        return $this;
    }
} 