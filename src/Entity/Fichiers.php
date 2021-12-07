<?php

namespace App\Entity;

use App\Repository\FichiersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FichiersRepository::class)
 */
class Fichiers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $fichierSTL = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerif;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="user")
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="fichiers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryFichier::class, inversedBy="fichiers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="fichier")
     */
    private $votes;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichierSTL(): ?array
    {
        return $this->fichierSTL;
    }

    public function setFichierSTL(string $fichierSTL): self
    {
        array_push($this->fichierSTL, $fichierSTL);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsVerif(): ?bool
    {
        return $this->isVerif;
    }

    public function setIsVerif(bool $isVerif): self
    {
        $this->isVerif = $isVerif;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this->getUser());
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?CategoryFichier
    {
        return $this->category;
    }

    public function setCategory(?CategoryFichier $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->getFichierSTL();
    // }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setFichier($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getFichier() === $this) {
                $vote->setFichier(null);
            }
        }

        return $this;
    }
}
