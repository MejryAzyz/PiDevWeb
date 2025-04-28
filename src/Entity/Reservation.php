<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservation')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_reservation = null;

    public function getId_reservation(): ?int
    {
        return $this->id_reservation;
    }

    public function setId_reservation(int $id_reservation): self
    {
        $this->id_reservation = $id_reservation;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_patient = null;

    public function getId_patient(): ?int
    {
        return $this->id_patient;
    }

    public function setId_patient(int $id_patient): self
    {
        $this->id_patient = $id_patient;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_clinique = null;

    public function getId_clinique(): ?int
    {
        return $this->id_clinique;
    }

    public function setId_clinique(int $id_clinique): self
    {
        $this->id_clinique = $id_clinique;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_transport = null;

    public function getId_transport(): ?int
    {
        return $this->id_transport;
    }

    public function setId_transport(int $id_transport): self
    {
        $this->id_transport = $id_transport;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $heure_depart = null;

    public function getHeure_depart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeure_depart(string $heure_depart): self
    {
        $this->heure_depart = $heure_depart;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_hebergement = null;

    public function getId_hebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function setId_hebergement(int $id_hebergement): self
    {
        $this->id_hebergement = $id_hebergement;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_debut = null;

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_fin = null;

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_reservation = null;

    public function getDate_reservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDate_reservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: AffectationAccompagnateur::class, mappedBy: 'reservation')]
    private Collection $affectationAccompagnateurs;

    #[ORM\ManyToOne(targetEntity: Docteur::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'id_docteur', referencedColumnName: 'id_docteur')]
    private ?Docteur $docteur = null;

    #[ORM\ManyToOne(targetEntity: Accompagnateur::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'id_accompagnateur', referencedColumnName: 'id_accompagnateur')]
    private ?Accompagnateur $accompagnateur = null;

    public function __construct()
    {
        $this->affectationAccompagnateurs = new ArrayCollection();
    }

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

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function getIdPatient(): ?int
    {
        return $this->id_patient;
    }

    public function setIdPatient(int $id_patient): static
    {
        $this->id_patient = $id_patient;

        return $this;
    }

    public function getIdClinique(): ?int
    {
        return $this->id_clinique;
    }

    public function setIdClinique(int $id_clinique): static
    {
        $this->id_clinique = $id_clinique;

        return $this;
    }

    public function getIdTransport(): ?int
    {
        return $this->id_transport;
    }

    public function setIdTransport(int $id_transport): static
    {
        $this->id_transport = $id_transport;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeureDepart(string $heure_depart): static
    {
        $this->heure_depart = $heure_depart;

        return $this;
    }

    public function getIdHebergement(): ?int
    {
        return $this->id_hebergement;
    }

    public function setIdHebergement(int $id_hebergement): static
    {
        $this->id_hebergement = $id_hebergement;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): static
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getDocteur(): ?Docteur
    {
        return $this->docteur;
    }

    public function setDocteur(?Docteur $docteur): self
    {
        $this->docteur = $docteur;
        return $this;
    }

    public function getAccompagnateur(): ?Accompagnateur
    {
        return $this->accompagnateur;
    }

    public function setAccompagnateur(?Accompagnateur $accompagnateur): self
    {
        $this->accompagnateur = $accompagnateur;
        return $this;
    }

}
