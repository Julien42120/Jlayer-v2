<?php

namespace App\Entity;

use App\Repository\CategoryFichierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryFichierRepository::class)
 */
class CategoryFichier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Fichiers::class, mappedBy="category")
     */
    private $fichiers;

    public function __construct()
    {
        $this->fichiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Fichiers[]
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichiers $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier;
            $fichier->setCategory($this);
        }

        return $this;
    }

    public function removeFichier(Fichiers $fichier): self
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getCategory() === $this) {
                $fichier->setCategory(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getCategory();
    }
}
