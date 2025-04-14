<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Specialite;
use Doctrine\Common\Collections\Collection;
use App\Entity\Planning_docteur;

#[ORM\Entity]
class Docteur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_docteur;

        #[ORM\ManyToOne(targetEntity: Clinique::class, inversedBy: "docteurs")]
    #[ORM\JoinColumn(name: 'id_clinique', referencedColumnName: 'id_clinique', onDelete: 'CASCADE')]
    private Clinique $id_clinique;

        #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: "docteurs")]
    #[ORM\JoinColumn(name: 'id_specialite', referencedColumnName: 'id_specialite', onDelete: 'CASCADE')]
    private Specialite $id_specialite;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "string", length: 255)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 20)]
    private string $telephone;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    public function getId_docteur()
    {
        return $this->id_docteur;
    }

    public function setId_docteur($value)
    {
        $this->id_docteur = $value;
    }

    public function getId_clinique()
    {
        return $this->id_clinique;
    }

    public function setId_clinique($value)
    {
        $this->id_clinique = $value;
    }

    public function getId_specialite()
    {
        return $this->id_specialite;
    }

    public function setId_specialite($value)
    {
        $this->id_specialite = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($value)
    {
        $this->prenom = $value;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($value)
    {
        $this->telephone = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_docteur", targetEntity: Planning_docteur::class)]
    private Collection $planning_docteurs;
}
