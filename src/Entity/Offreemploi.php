<?php

namespace App\Entity;

use App\Repository\OffreemploiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Postulation;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OffreemploiRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Offreemploi
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const JOB_TYPES = [
        'Full Time' => 'Full Time',
        'Part Time' => 'Part Time',
        'Contract' => 'Contract',
        'Internship' => 'Internship',
        'Temporary' => 'Temporary'
    ];

    public const CONTRACT_TYPES = [
        'CDI' => 'CDI',
        'CDD' => 'CDD',
        'Freelance' => 'Freelance',
        'Internship' => 'Internship',
        'Apprenticeship' => 'Apprenticeship'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title is required')]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Description is required')]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Job type is required')]
    #[Assert\Choice(callback: 'getJobTypes', message: 'Please select a valid job type')]
    private ?string $typeposte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datepublication = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageurl = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Status is required')]
    #[Assert\Choice(choices: ['active', 'inactive'], message: 'Please select a valid status')]
    private ?string $etat = 'active';

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Contract type is required')]
    #[Assert\Choice(callback: 'getContractTypes', message: 'Please select a valid contract type')]
    private ?string $typecontrat = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Location is required')]
    #[Assert\Length(max: 50)]
    private ?string $emplacement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(mappedBy: "id_offre", targetEntity: Postulation::class)]
    private Collection $postulations;

    private ?File $imageFile = null;

    public function __construct()
    {
        $this->postulations = new ArrayCollection();
        $this->datepublication = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->etat = self::STATUS_ACTIVE;
    }

    #[ORM\PrePersist]
    public function setTimestamps(): void
    {
        $this->datepublication = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTypeposte(): ?string
    {
        return $this->typeposte;
    }

    public function setTypeposte(string $typeposte): static
    {
        $this->typeposte = $typeposte;
        return $this;
    }

    public function getDatepublication(): ?\DateTimeInterface
    {
        return $this->datepublication;
    }

    public function setDatepublication(?\DateTimeInterface $datepublication): static
    {
        $this->datepublication = $datepublication;
        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): static
    {
        $this->imageurl = $imageurl;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getTypecontrat(): ?string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(string $typecontrat): static
    {
        $this->typecontrat = $typecontrat;
        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getPostulations(): Collection
    {
        return $this->postulations;
    }

    public function addPostulation(Postulation $postulation): self
    {
        if (!$this->postulations->contains($postulation)) {
            $this->postulations[] = $postulation;
            $postulation->setIdOffre($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): self
    {
        if ($this->postulations->removeElement($postulation)) {
            if ($postulation->getIdOffre() === $this) {
                $postulation->setIdOffre(null);
            }
        }

        return $this;
    }

    public static function getJobTypes(): array
    {
        return array_keys(self::JOB_TYPES);
    }

    public static function getContractTypes(): array
    {
        return array_keys(self::CONTRACT_TYPES);
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        return $this;
    }
}