<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Postulation;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
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
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $titre = '';

    #[ORM\Column(type: "text", options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    private string $description = '';

    #[ORM\Column(type: "string", length: 100, options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: self::JOB_TYPES)]
    private string $typeposte = '';

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $datepublication = null;

    #[ORM\Column(type: "string", length: 255, nullable: true, options: ["collation" => "utf8mb4_general_ci"])]
    private ?string $imageurl = null;

    #[ORM\Column(type: "string", length: 255, options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::STATUS_ACTIVE, self::STATUS_INACTIVE])]
    private string $etat = self::STATUS_ACTIVE;

    #[ORM\Column(type: "string", length: 20, options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: self::CONTRACT_TYPES)]
    private string $typecontrat = '';

    #[ORM\Column(type: "string", length: 50, options: ["collation" => "utf8mb4_general_ci"])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $emplacement = '';

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(mappedBy: "id_offre", targetEntity: Postulation::class)]
    private Collection $postulations;

    public function __construct()
    {
        $this->postulations = new ArrayCollection();
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

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getTypeposte(): string
    {
        return $this->typeposte;
    }

    public function setTypeposte(string $typeposte): self
    {
        $this->typeposte = $typeposte;
        return $this;
    }

    public function getDatepublication(): ?\DateTimeInterface
    {
        return $this->datepublication;
    }

    public function setDatepublication(?\DateTimeInterface $datepublication): self
    {
        $this->datepublication = $datepublication;
        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): self
    {
        $this->imageurl = $imageurl;
        return $this;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getTypecontrat(): string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(string $typecontrat): self
    {
        $this->typecontrat = $typecontrat;
        return $this;
    }

    public function getEmplacement(): string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
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
}