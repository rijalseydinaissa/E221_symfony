<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeProduitRepository;

#[ORM\Entity(repositoryClass: CommandeProduitRepository::class)]
class CommandeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeProduits')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Commande $commande;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Produit $produit;

    #[ORM\Column]
    private int $quantite;

    #[ORM\Column]
    private float $prixAchat;

    public function __toString(): string
    {
        return 'Produit en commande #' . ($this->id ?? 'nouveau') . ' - Qté: ' . $this->quantite;
    }

    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCommande(): ?Commande
    {
        return $this->commande;
    }
    public function getProduit(): ?Produit
    {
        return $this->produit;
    }
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }
    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }
    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }
    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }
    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
    public function setPrixAchat(float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;
        return $this;
    }
}