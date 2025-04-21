<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $code;

    #[ORM\Column(length: 255)]
    private string $designation;

    #[ORM\Column]
    private int $quantiteStock;

    #[ORM\Column]
    private float $prixUnitaire;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private SousCategorie $sousCategorie;

    #[ORM\Column]
    private bool $isArchived = false;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: CommandeProduit::class)]
    private Collection $commandeProduits;

    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCode(): ?string
    {
        return $this->code;
    }
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }
    public function getDesignation(): ?string
    {
        return $this->designation;
    }
    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;
        return $this;
    }
    public function getQuantiteStock(): ?int
    {
        return $this->quantiteStock;
    }
    public function setQuantiteStock(int $quantiteStock): self
    {
        $this->quantiteStock = $quantiteStock;
        return $this;
    }
    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }
    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }
    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sousCategorie;
    }
    public function setSousCategorie(?SousCategorie $sousCategorie): self
    {
        $this->sousCategorie = $sousCategorie;
        return $this;
    }
    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }
    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;
        return $this;
    }
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }
}