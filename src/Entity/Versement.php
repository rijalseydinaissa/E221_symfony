<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VersementRepository;

#[ORM\Entity(repositoryClass: VersementRepository::class)]
class Versement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $numero;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;


    #[ORM\Column]
    private float $montant;

    #[ORM\ManyToOne(inversedBy: 'versements')]
    #[ORM\JoinColumn(nullable: false)]
    private Commande $commande;

    public function __construct()
    {
        $this->date = new \DateTime();
    }


    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNumero(): ?string
    {
        return $this->numero;
    }
    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getMontant(): ?float
    {
        return $this->montant;
    }
    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }
    public function getCommande(): ?Commande
    {
        return $this->commande;
    }
    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }
    public function __toString()
    {
        return $this->numero;
    }
}
