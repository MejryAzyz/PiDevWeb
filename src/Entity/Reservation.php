<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Clinique;
use App\Entity\Transport;
use App\Repository\ReservationRepository;
use App\Entity\Hebergement; // Import the Hebergement entity

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

    // Define the ManyToOne relationship with Clinique
    #[ORM\ManyToOne(targetEntity: Clinique::class)]
    #[ORM\JoinColumn(name: 'id_clinique', referencedColumnName: 'id_clinique', nullable: false)]
    private ?Clinique $idClinique = null;

    public function getIdClinique(): ?Clinique
    {
        return $this->idClinique;
    }

    public function setIdClinique(?Clinique $idClinique): self
    {
        $this->idClinique = $idClinique;
        return $this;
    }


    #[ORM\ManyToOne(targetEntity: Transport::class)]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id_transport', nullable: false)]
    private ?Transport $id_transport = null;

    // Getter for transport
    public function getid_transport(): ?Transport
    {
        return $this->id_transport;
    }

    // Setter for transport
    public function setid_transport(?Transport $transport): self
    {
        $this->id_transport = $transport;
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

    #[ORM\ManyToOne(targetEntity: Hebergement::class)]
    #[ORM\JoinColumn(name: 'id_hebergement', referencedColumnName: 'id_hebergement', nullable: false)]
    private ?Hebergement $id_hebergement = null; // Define the relation with Hebergement

    // Getter for hebergement
    public function getid_hebergement(): ?Hebergement
    {
        return $this->id_hebergement;
    }

    // Setter for hebergement
    public function setid_hebergement(?Hebergement $hebergement): self
    {
        $this->id_hebergement = $hebergement;
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

    #[ORM\OneToMany(targetEntity: AffectationAccompagnateur::class, mappedBy: 'reservation')]
    private Collection $affectationAccompagnateurs;

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
}
