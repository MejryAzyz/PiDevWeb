<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CliniquePhotos
 *
 * @ORM\Table(name="clinique_photos", indexes={@ORM\Index(name="IDX_16B9EBA3265183A3", columns={"clinique_id"})})
 * @ORM\Entity
 */
class CliniquePhotos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_photo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_url", type="string", length=255, nullable=false)
     */
    private $photoUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded_at", type="datetime", nullable=false)
     */
    private $uploadedAt;

    /**
     * @var \Clinique
     *
     * @ORM\ManyToOne(targetEntity="Clinique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clinique_id", referencedColumnName="id_clinique")
     * })
     */
    private $clinique;


}
