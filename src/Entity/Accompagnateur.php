<?php

namespace App\Entity;

<<<<<<< HEAD
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AccompagnateurRepository;

#[ORM\Entity(repositoryClass: AccompagnateurRepository::class)]
#[ORM\Table(name: 'accompagnateur')]
class Accompagnateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_accompagnateur = null;

    public function getId_accompagnateur(): ?int
=======
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Postulation;

#[ORM\Entity]
class Accompagnateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_accompagnateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password_hash;

    #[ORM\Column(type: "string", length: 150)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $fichier_cv;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_profil;

    #[ORM\Column(type: "text")]
    private string $experience;

    #[ORM\Column(type: "text")]
    private string $motivation;

    #[ORM\Column(type: "string", length: 255)]
    private string $langues;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_recrutement;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_inscription;

    public function getId_accompagnateur()
>>>>>>> c4098f6 (bundle)
    {
        return $this->id_accompagnateur;
    }

<<<<<<< HEAD
    public function setId_accompagnateur(int $id_accompagnateur): self
    {
        $this->id_accompagnateur = $id_accompagnateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $username = null;

    public function getUsername(): ?string
=======
    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getUsername()
>>>>>>> c4098f6 (bundle)
    {
        return $this->username;
    }

<<<<<<< HEAD
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password_hash = null;

    public function getPassword_hash(): ?string
=======
    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword_hash()
>>>>>>> c4098f6 (bundle)
    {
        return $this->password_hash;
    }

<<<<<<< HEAD
    public function setPassword_hash(string $password_hash): self
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $email = null;

    public function getEmail(): ?string
=======
    public function setPassword_hash($value)
    {
        $this->password_hash = $value;
    }

    public function getEmail()
>>>>>>> c4098f6 (bundle)
    {
        return $this->email;
    }

<<<<<<< HEAD
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $fichier_cv = null;

    public function getFichier_cv(): ?string
=======
    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getFichier_cv()
>>>>>>> c4098f6 (bundle)
    {
        return $this->fichier_cv;
    }

<<<<<<< HEAD
    public function setFichier_cv(string $fichier_cv): self
    {
        $this->fichier_cv = $fichier_cv;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $photo_profil = null;

    public function getPhoto_profil(): ?string
=======
    public function setFichier_cv($value)
    {
        $this->fichier_cv = $value;
    }

    public function getPhoto_profil()
>>>>>>> c4098f6 (bundle)
    {
        return $this->photo_profil;
    }

<<<<<<< HEAD
    public function setPhoto_profil(string $photo_profil): self
    {
        $this->photo_profil = $photo_profil;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $experience = null;

    public function getExperience(): ?string
=======
    public function setPhoto_profil($value)
    {
        $this->photo_profil = $value;
    }

    public function getExperience()
>>>>>>> c4098f6 (bundle)
    {
        return $this->experience;
    }

<<<<<<< HEAD
    public function setExperience(string $experience): self
    {
        $this->experience = $experience;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $motivation = null;

    public function getMotivation(): ?string
=======
    public function setExperience($value)
    {
        $this->experience = $value;
    }

    public function getMotivation()
>>>>>>> c4098f6 (bundle)
    {
        return $this->motivation;
    }

<<<<<<< HEAD
    public function setMotivation(string $motivation): self
    {
        $this->motivation = $motivation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $langues = null;

    public function getLangues(): ?string
=======
    public function setMotivation($value)
    {
        $this->motivation = $value;
    }

    public function getLangues()
>>>>>>> c4098f6 (bundle)
    {
        return $this->langues;
    }

<<<<<<< HEAD
    public function setLangues(string $langues): self
    {
        $this->langues = $langues;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = null;

    public function getStatut(): ?string
=======
    public function setLangues($value)
    {
        $this->langues = $value;
    }

    public function getStatut()
>>>>>>> c4098f6 (bundle)
    {
        return $this->statut;
    }

<<<<<<< HEAD
    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_recrutement = null;

    public function getDate_recrutement(): ?\DateTimeInterface
=======
    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getDate_recrutement()
>>>>>>> c4098f6 (bundle)
    {
        return $this->date_recrutement;
    }

<<<<<<< HEAD
    public function setDate_recrutement(\DateTimeInterface $date_recrutement): self
    {
        $this->date_recrutement = $date_recrutement;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: AffectationAccompagnateur::class, mappedBy: 'accompagnateur')]
    private Collection $affectationAccompagnateurs;

    /**
     * @return Collection<int, AffectationAccompagnateur>
     */
    public function getAffectationAccompagnateurs(): Collection
    {
        if (!$this->affectationAccompagnateurs instanceof Collection) {
            $this->affectationAccompagnateurs = new ArrayCollection();
        }
        return $this->affectationAccompagnateurs;
    }

    public function addAffectationAccompagnateur(AffectationAccompagnateur $affectationAccompagnateur): self
    {
        if (!$this->getAffectationAccompagnateurs()->contains($affectationAccompagnateur)) {
            $this->getAffectationAccompagnateurs()->add($affectationAccompagnateur);
        }
        return $this;
    }

    public function removeAffectationAccompagnateur(AffectationAccompagnateur $affectationAccompagnateur): self
    {
        $this->getAffectationAccompagnateurs()->removeElement($affectationAccompagnateur);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: PlanningAccompagnateur::class, mappedBy: 'accompagnateur')]
    private Collection $planningAccompagnateurs;

    public function __construct()
    {
        $this->affectationAccompagnateurs = new ArrayCollection();
        $this->planningAccompagnateurs = new ArrayCollection();
    }

    /**
     * @return Collection<int, PlanningAccompagnateur>
     */
    public function getPlanningAccompagnateurs(): Collection
    {
        if (!$this->planningAccompagnateurs instanceof Collection) {
            $this->planningAccompagnateurs = new ArrayCollection();
        }
        return $this->planningAccompagnateurs;
    }

    public function addPlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): self
    {
        if (!$this->getPlanningAccompagnateurs()->contains($planningAccompagnateur)) {
            $this->getPlanningAccompagnateurs()->add($planningAccompagnateur);
        }
        return $this;
    }

    public function removePlanningAccompagnateur(PlanningAccompagnateur $planningAccompagnateur): self
    {
        $this->getPlanningAccompagnateurs()->removeElement($planningAccompagnateur);
        return $this;
    }

    public function getIdAccompagnateur(): ?int
    {
        return $this->id_accompagnateur;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getFichierCv(): ?string
    {
        return $this->fichier_cv;
    }

    public function setFichierCv(string $fichier_cv): static
    {
        $this->fichier_cv = $fichier_cv;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photo_profil;
    }

    public function setPhotoProfil(string $photo_profil): static
    {
        $this->photo_profil = $photo_profil;

        return $this;
    }

    public function getDateRecrutement(): ?\DateTimeInterface
    {
        return $this->date_recrutement;
    }

    public function setDateRecrutement(\DateTimeInterface $date_recrutement): static
    {
        $this->date_recrutement = $date_recrutement;

        return $this;
    }

=======
    public function setDate_recrutement($value)
    {
        $this->date_recrutement = $value;
    }

    public function getDate_inscription()
    {
        return $this->date_inscription;
    }

    public function setDate_inscription($value)
    {
        $this->date_inscription = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Planning_accompagnateur::class)]
    private Collection $planning_accompagnateurs;

        public function getPlanning_accompagnateurs(): Collection
        {
            return $this->planning_accompagnateurs;
        }
    
        public function addPlanning_accompagnateur(Planning_accompagnateur $planning_accompagnateur): self
        {
            if (!$this->planning_accompagnateurs->contains($planning_accompagnateur)) {
                $this->planning_accompagnateurs[] = $planning_accompagnateur;
                $planning_accompagnateur->setId_accompagnateur($this);
            }
    
            return $this;
        }
    
        public function removePlanning_accompagnateur(Planning_accompagnateur $planning_accompagnateur): self
        {
            if ($this->planning_accompagnateurs->removeElement($planning_accompagnateur)) {
                // set the owning side to null (unless already changed)
                if ($planning_accompagnateur->getId_accompagnateur() === $this) {
                    $planning_accompagnateur->setId_accompagnateur(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Affectation_accompagnateur::class)]
    private Collection $affectation_accompagnateurs;

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Postulation::class)]
    private Collection $postulations;
>>>>>>> c4098f6 (bundle)
}
