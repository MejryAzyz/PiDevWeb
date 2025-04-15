<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UtilisateurRepository;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_utilisateur = null;

    public function getId_utilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function setId_utilisateur(int $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(name: 'id_role', referencedColumnName: 'id_role')]
    private ?Role $role = null;

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;
        return $this;
    }

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
    private ?string $mot_de_passe = null;

    public function getMot_de_passe(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMot_de_passe(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;
        return $this;
    }

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

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_naissance = null;

    public function getDate_naissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDate_naissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $image_url = null;

    public function getImage_url(): ?string
    {
        return $this->image_url;
    }

    public function setImage_url(string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $status = null;

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $verif = null;

    public function getVerif(): ?int
    {
        return $this->verif;
    }

    public function setVerif(int $verif): self
    {
        $this->verif = $verif;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $verification_token = null;

    public function getVerification_token(): ?string
    {
        return $this->verification_token;
    }

    public function setVerification_token(string $verification_token): self
    {
        $this->verification_token = $verification_token;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nationalite = null;

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $reset_password_token = null;

    public function getReset_password_token(): ?string
    {
        return $this->reset_password_token;
    }

    public function setReset_password_token(string $reset_password_token): self
    {
        $this->reset_password_token = $reset_password_token;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReservationHebergement::class, mappedBy: 'utilisateur')]
    private Collection $reservationHebergements;

    /**
     * @return Collection<int, ReservationHebergement>
     */
    public function getReservationHebergements(): Collection
    {
        if (!$this->reservationHebergements instanceof Collection) {
            $this->reservationHebergements = new ArrayCollection();
        }
        return $this->reservationHebergements;
    }

    public function addReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        if (!$this->getReservationHebergements()->contains($reservationHebergement)) {
            $this->getReservationHebergements()->add($reservationHebergement);
        }
        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        $this->getReservationHebergements()->removeElement($reservationHebergement);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReservationTransport::class, mappedBy: 'utilisateur')]
    private Collection $reservationTransports;

    public function __construct()
    {
        $this->reservationHebergements = new ArrayCollection();
        $this->reservationTransports = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReservationTransport>
     */
    public function getReservationTransports(): Collection
    {
        if (!$this->reservationTransports instanceof Collection) {
            $this->reservationTransports = new ArrayCollection();
        }
        return $this->reservationTransports;
    }

    public function addReservationTransport(ReservationTransport $reservationTransport): self
    {
        if (!$this->getReservationTransports()->contains($reservationTransport)) {
            $this->getReservationTransports()->add($reservationTransport);
        }
        return $this;
    }

    public function removeReservationTransport(ReservationTransport $reservationTransport): self
    {
        $this->getReservationTransports()->removeElement($reservationTransport);
        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verification_token;
    }

    public function setVerificationToken(string $verification_token): static
    {
        $this->verification_token = $verification_token;

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->reset_password_token;
    }

    public function setResetPasswordToken(string $reset_password_token): static
    {
        $this->reset_password_token = $reset_password_token;

        return $this;
    }

}
