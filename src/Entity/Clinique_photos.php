<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'clinique_photos')]
class Clinique_photos
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_photo;

    #[ORM\ManyToOne(targetEntity: Clinique::class, inversedBy: "cliniquePhotos")]
    #[ORM\JoinColumn(name: 'clinique_id', referencedColumnName: 'id_clinique', nullable: true)]
    private ?Clinique $clinique_id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_url;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $uploaded_at;

    public function getId_photo(): ?int
    {
        return $this->id_photo;
    }

    public function setId_photo(int $id_photo): self
    {
        $this->id_photo = $id_photo;
        return $this;
    }

    public function getCliniqueId(): ?Clinique
    {
        return $this->clinique_id;
    }

    public function setCliniqueId(?Clinique $clinique): self
    {
        $this->clinique_id = $clinique;
        return $this;
    }

    public function getPhoto_url(): ?string
    {
        return $this->photo_url;
    }

    public function setPhoto_url(string $photo_url): self
    {
        $this->photo_url = $photo_url;
        return $this;
    }

    public function getUploaded_at(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploaded_at(\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;
        return $this;
    }
}
