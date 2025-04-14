<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Paiement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_paiement;

    #[ORM\Column(type: "integer")]
    private int $id_reservation;

    #[ORM\Column(type: "float")]
    private float $montant;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_paiement;

    #[ORM\Column(type: "string", length: 255)]
    private string $methode;

    public function getId_paiement()
    {
        return $this->id_paiement;
    }

    public function setId_paiement($value)
    {
        $this->id_paiement = $value;
    }

    public function getId_reservation()
    {
        return $this->id_reservation;
    }

    public function setId_reservation($value)
    {
        $this->id_reservation = $value;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($value)
    {
        $this->montant = $value;
    }

    public function getDate_paiement()
    {
        return $this->date_paiement;
    }

    public function setDate_paiement($value)
    {
        $this->date_paiement = $value;
    }

    public function getMethode()
    {
        return $this->methode;
    }

    public function setMethode($value)
    {
        $this->methode = $value;
    }
}
