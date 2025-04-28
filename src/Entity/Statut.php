<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, PlanningDocteur>
     */
    #[ORM\OneToMany(targetEntity: PlanningDocteur::class, mappedBy: 'statut')]
    private Collection $PlanningDocteur;

    /**
     * @var Collection<int, PlanningAccompagnateur>
     */
    #[ORM\OneToMany(targetEntity: PlanningAccompagnateur::class, mappedBy: 'statut')]
    private Collection $PlanningAccompagnateur;

    public function __construct()
    {
        $this->PlanningDocteur = new ArrayCollection();
        $this->PlanningAccompagnateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, PlanningDocteur>
     */
    public function getPlanningDocteur(): Collection
    {
        return $this->PlanningDocteur;
    }

    public function addPlanningDocteur(PlanningDocteur $planningDocteur): static
    {
        if (!$this->PlanningDocteur->contains($planningDocteur)) {
            $this->PlanningDocteur->add($planningDocteur);
            $planningDocteur->setStatut($this);
        }

        return $this;
    }

    public function removePlanningDocteur(PlanningDocteur $planningDocteur): static
    {
        if ($this->PlanningDocteur->removeElement($planningDocteur)) {
            // set the owning side to null (unless already changed)
            if ($planningDocteur->getStatut() === $this) {
                $planningDocteur->setStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlanningAccompagnateur>
     */
    public function getPlanningAccompagnateur(): Collection
    {
        return $this->PlanningAccompagnateur;
    }

    public function addPlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): static
    {
        if (!$this->PlanningAccompagnateur->contains($planningAccompagnateur)) {
            $this->PlanningAccompagnateur->add($planningAccompagnateur);
            $planningAccompagnateur->setStatut($this);
        }

        return $this;
    }

    public function removePlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): static
    {
        if ($this->PlanningAccompagnateur->removeElement($planningAccompagnateur)) {
            // set the owning side to null (unless already changed)
            if ($planningAccompagnateur->getStatut() === $this) {
                $planningAccompagnateur->setStatut(null);
            }
        }

        return $this;
    }
}
