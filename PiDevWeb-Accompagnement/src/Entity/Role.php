<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Utilisateur;

#[ORM\Entity]
class Role
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_role;

    #[ORM\Column(type: "string", length: 50)]
    private string $nom;

    public function getId_role()
    {
        return $this->id_role;
    }

    public function setId_role($value)
    {
        $this->id_role = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_role", targetEntity: Utilisateur::class)]
    private Collection $utilisateurs;

        public function getUtilisateurs(): Collection
        {
            return $this->utilisateurs;
        }
    
        public function addUtilisateur(Utilisateur $utilisateur): self
        {
            if (!$this->utilisateurs->contains($utilisateur)) {
                $this->utilisateurs[] = $utilisateur;
                $utilisateur->setId_role($this);
            }
    
            return $this;
        }
    
        public function removeUtilisateur(Utilisateur $utilisateur): self
        {
            if ($this->utilisateurs->removeElement($utilisateur)) {
                // set the owning side to null (unless already changed)
                if ($utilisateur->getId_role() === $this) {
                    $utilisateur->setId_role(null);
                }
            }
    
            return $this;
        }
}
