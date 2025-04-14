<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Clinique;

#[ORM\Entity]
class Clinique_photos
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_photo;

        #[ORM\ManyToOne(targetEntity: Clinique::class, inversedBy: "clinique_photoss")]
    #[ORM\JoinColumn(name: 'clinique_id', referencedColumnName: 'id_clinique', onDelete: 'CASCADE')]
    private Clinique $clinique_id;

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

    public function getClinique_id()
    {
        return $this->clinique_id;
    }

    public function setClinique_id($value)
    {
        $this->clinique_id = $value;
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
