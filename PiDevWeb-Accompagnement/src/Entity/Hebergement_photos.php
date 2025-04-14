<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Hebergement;

#[ORM\Entity]
class Hebergement_photos
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_photo;

        #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: "hebergement_photoss")]
    #[ORM\JoinColumn(name: 'hebergement_id', referencedColumnName: 'id_hebergement', onDelete: 'CASCADE')]
    private Hebergement $hebergement_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_url;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $uploaded_at;

    public function getId_photo()
    {
        return $this->id_photo;
    }

    public function setId_photo($value)
    {
        $this->id_photo = $value;
    }

    public function getHebergement_id()
    {
        return $this->hebergement_id;
    }

    public function setHebergement_id($value)
    {
        $this->hebergement_id = $value;
    }

    public function getPhoto_url()
    {
        return $this->photo_url;
    }

    public function setPhoto_url($value)
    {
        $this->photo_url = $value;
    }

    public function getUploaded_at()
    {
        return $this->uploaded_at;
    }

    public function setUploaded_at($value)
    {
        $this->uploaded_at = $value;
    }
}
