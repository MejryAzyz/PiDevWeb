<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\HebergementPhotoRepository;

#[ORM\Entity(repositoryClass: HebergementPhotoRepository::class)]
#[ORM\Table(name: 'hebergement_photos')]
class HebergementPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_photo = null;

    public function getId_photo(): ?int
    {
        return $this->id_photo;
    }

    public function setId_photo(int $id_photo): self
    {
        $this->id_photo = $id_photo;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: 'hebergementPhotos')]
    #[ORM\JoinColumn(name: 'hebergement_id', referencedColumnName: 'id_hebergement')]
    private ?Hebergement $hebergement = null;

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): self
    {
        $this->hebergement = $hebergement;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $photo_url = null;

    public function getPhoto_url(): ?string
    {
        return $this->photo_url;
    }

    public function setPhoto_url(string $photo_url): self
    {
        $this->photo_url = $photo_url;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $uploaded_at = null;

    public function getUploaded_at(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploaded_at(\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;
        return $this;
    }

    public function getIdPhoto(): ?int
    {
        return $this->id_photo;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(string $photo_url): static
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTimeInterface $uploaded_at): static
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }

}
