<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\SousCategorieRepository;

#[ORM\Entity(repositoryClass: SousCategorieRepository::class)]
class SousCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\ManyToOne(inversedBy: 'sousCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private Categorie $categorie;

    #[ORM\OneToMany(mappedBy: 'sousCategorie', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\Column]
    private bool $isArchived = false;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }
    public function setIsArchived(bool $isArchived): self{
        $this->isArchived = $isArchived;
        return $this;
    }
    // public function addProduit(Produit $produit): self
    // {
    //     if (!$this->produits->contains($produit)) {
    //         $this->produits[] = $produit;
    //         $produit->setSousCategorie($this);
    //     }

    //     return $this;
    // }
}
