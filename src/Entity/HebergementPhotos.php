<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HebergementPhotos
 *
 * @ORM\Table(name="hebergement_photos", indexes={@ORM\Index(name="IDX_6BFEF89A23BB0F66", columns={"hebergement_id"})})
 * @ORM\Entity
 */
class HebergementPhotos
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
     * @var \Hebergement
     *
     * @ORM\ManyToOne(targetEntity="Hebergement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hebergement_id", referencedColumnName="id_hebergement")
     * })
     */
    private $hebergement;


}
