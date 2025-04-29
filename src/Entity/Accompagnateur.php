<?php

namespace App\Entity;

use App\Repository\AccompagnateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Postulation;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AccompagnateurRepository::class)]
class Accompagnateur implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_accompagnateur = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur est obligatoire.")]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\Length(
        min: 6,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères."
    )]
    private string $password_hash;

    #[ORM\Column(type: "string", length: 150)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas un email valide.")]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $fichier_cv;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_profil;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "L'expérience est obligatoire.")]
    private string $experience;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La motivation est obligatoire.")]
    private string $motivation;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La langue est obligatoire.")]
    private string $langues;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\Choice(choices: ['Actif', 'Inactif','Pending'], message: "Statut invalide.")]
    private string $statut;

    #[ORM\Column(type: "date")]
    #[Assert\LessThanOrEqual("today", message: "La date de recrutement doit être aujourd'hui ou dans le passé.")]
    private \DateTimeInterface $date_recrutement;

    #[ORM\Column(type: "datetime")]
    #[Assert\LessThanOrEqual("today", message: "La date d'inscription doit être aujourd'hui ou dans le passé.")]
    private \DateTimeInterface $date_inscription;

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Planning_accompagnateur::class)]
    private Collection $planning_accompagnateurs;

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Affectation_accompagnateur::class)]
    private Collection $affectation_accompagnateurs;

    #[ORM\OneToMany(mappedBy: "id_accompagnateur", targetEntity: Postulation::class)]
    private Collection $postulations;

    public function __construct()
    {
        $this->planning_accompagnateurs = new ArrayCollection();
        $this->affectation_accompagnateurs = new ArrayCollection();
        $this->postulations = new ArrayCollection();
    }


    public function getId_accompagnateur(): ?int
    {
        return $this->id_accompagnateur;
    }

    public function setId_accompagnateur($value)
    {
        $this->id_accompagnateur = $value;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getFichier_cv()
    {
        return $this->fichier_cv;
    }

    public function setFichier_cv($value)
    {
        $this->fichier_cv = $value;
    }

    public function getPhoto_profil()
    {
        return $this->photo_profil;
    }

    public function setPhoto_profil($value)
    {
        $this->photo_profil = $value;
    }

    public function getExperience()
    {
        return $this->experience;
    }

    public function setExperience($value)
    {
        $this->experience = $value;
    }

    public function getMotivation()
    {
        return $this->motivation;
    }

    public function setMotivation($value)
    {
        $this->motivation = $value;
    }

    public function getLangues()
    {
        return $this->langues;
    }

    public function setLangues($value)
    {
        $this->langues = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getDate_recrutement()
    {
        return $this->date_recrutement;
    }

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

    public function getAffectation_accompagnateurs(): Collection
    {
        return $this->affectation_accompagnateurs;
    }

    public function getPostulations(): Collection
    {
        return $this->postulations;
    }
}