<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Postulation;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Offreemploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint")]
    private ?string $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $titre = '';

    #[ORM\Column(type: "text")]
    private string $description = '';

    #[ORM\Column(type: "string", length: 100)]
    private string $typeposte = '';

    #[ORM\Column(type: "date")]
    private ?\DateTime $datepublication = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $imageurl = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $etat = '';

    #[ORM\Column(type: "string", length: 20)]
    private string $typecontrat = '';

    #[ORM\Column(type: "string", length: 50)]
    private string $emplacement = '';

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $updatedAt = null;

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
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // Getters and Setters
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getDatepublication(): ?\DateTime
    {
        return $this->datepublication;
    }

    public function setDatepublication(?\DateTime $datepublication): self
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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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