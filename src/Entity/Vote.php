<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="votes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=fichiers::class, inversedBy="votes", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $fichier;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFichier(): ?fichiers
    {
        return $this->fichier;
    }

    public function setFichier(?fichiers $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }
}
