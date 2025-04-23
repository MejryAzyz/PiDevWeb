<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Offre_emploi
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $titre;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "string", length: 100)]
    private string $type_poste;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date_publication;

    #[ORM\Column(type: "string", length: 255)]
    private string $image_url;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($value)
    {
        $this->titre = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getType_poste()
    {
        return $this->type_poste;
    }

    public function setType_poste($value)
    {
        $this->type_poste = $value;
    }

    public function getDate_publication()
    {
        return $this->date_publication;
    }

    public function setDate_publication($value)
    {
        $this->date_publication = $value;
    }

    public function getImage_url()
    {
        return $this->image_url;
    }

    public function setImage_url($value)
    {
        $this->image_url = $value;
    }

    public function getTypePoste(): ?string
    {
        return $this->type_poste;
    }

    public function setTypePoste(string $type_poste): static
    {
        $this->type_poste = $type_poste;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

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
}
