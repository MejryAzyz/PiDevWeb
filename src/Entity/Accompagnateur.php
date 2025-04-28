<?php

namespace App\Entity;

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
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur(int $id_accompagnateur): self
    {
        $this->id_accompagnateur = $id_accompagnateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $username = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password_hash = null;

    public function getPassword_hash(): ?string
    {
        return $this->password_hash;
    }

    public function setPassword_hash(string $password_hash): self
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $fichier_cv = null;

    public function getFichier_cv(): ?string
    {
        return $this->fichier_cv;
    }

    public function setFichier_cv(string $fichier_cv): self
    {
        $this->fichier_cv = $fichier_cv;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $photo_profil = null;

    public function getPhoto_profil(): ?string
    {
        return $this->photo_profil;
    }

    public function setPhoto_profil(string $photo_profil): self
    {
        $this->photo_profil = $photo_profil;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $experience = null;

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $motivation = null;

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): self
    {
        $this->motivation = $motivation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $langues = null;

    public function getLangues(): ?string
    {
        return $this->langues;
    }

    public function setLangues(string $langues): self
    {
        $this->langues = $langues;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_recrutement = null;

    public function getDate_recrutement(): ?\DateTimeInterface
    {
        return $this->date_recrutement;
    }

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

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'accompagnateur')]
    private Collection $reservations;

    public function __construct()
    {
        $this->affectationAccompagnateurs = new ArrayCollection();
        $this->planningAccompagnateurs = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        if (!$this->reservations instanceof Collection) {
            $this->reservations = new ArrayCollection();
        }
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->getReservations()->contains($reservation)) {
            $this->getReservations()->add($reservation);
            $reservation->setAccompagnateur($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->getReservations()->removeElement($reservation)) {
            if ($reservation->getAccompagnateur() === $this) {
                $reservation->setAccompagnateur(null);
            }
        }
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

}
