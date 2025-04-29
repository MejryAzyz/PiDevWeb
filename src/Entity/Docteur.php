<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\DocteurRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocteurRepository::class)]
#[ORM\Table(name: 'docteur')]
class Docteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_docteur = null;

    public function getId_docteur(): ?int
    {
        return $this->id_docteur;
    }

    public function setId_docteur(int $id_docteur): self
    {
        $this->id_docteur = $id_docteur;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Clinique::class, inversedBy: 'docteurs')]
    #[ORM\JoinColumn(name: 'id_clinique', referencedColumnName: 'id_clinique')]
    private ?Clinique $clinique = null;

    public function getClinique(): ?Clinique
    {
        return $this->clinique;
    }

    public function setClinique(?Clinique $clinique): self
    {
        $this->clinique = $clinique;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: 'docteurs')]
    #[ORM\JoinColumn(name: 'id_specialite', referencedColumnName: 'id_specialite')]
    private ?Specialite $specialite = null;

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }

    #[Assert\NotBlank(message: 'Le nom est requis')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[Assert\NotBlank(message: 'Le prénom est requis')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'
    )]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }


    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
    pattern: "/^\+?[0-9]{8,15}$/",
    message: "Le numéro de téléphone doit être composé de 8 à 15 chiffres, avec un '+' facultatif au début."
    )]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $telephone = null;

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    #[Assert\NotBlank(message: 'L\'email est requis')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
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

    #[ORM\OneToMany(targetEntity: PlanningDocteur::class, mappedBy: 'docteur')]
    private Collection $planningDocteurs;

    public function __construct()
    {
        $this->planningDocteurs = new ArrayCollection();
    }

    /**
     * @return Collection<int, PlanningDocteur>
     */
    public function getPlanningDocteurs(): Collection
    {
        if (!$this->planningDocteurs instanceof Collection) {
            $this->planningDocteurs = new ArrayCollection();
        }
        return $this->planningDocteurs;
    }

    public function addPlanningDocteur(PlanningDocteur $planningDocteur): self
    {
        if (!$this->getPlanningDocteurs()->contains($planningDocteur)) {
            $this->getPlanningDocteurs()->add($planningDocteur);
        }
        return $this;
    }

    public function removePlanningDocteur(PlanningDocteur $planningDocteur): self
    {
        $this->getPlanningDocteurs()->removeElement($planningDocteur);
        return $this;
    }

    public function getIdDocteur(): ?int
    {
        return $this->id_docteur;
    }

}
