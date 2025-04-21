<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\CommandeRepository;
use App\Validator\Constraints as AppAssert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[AppAssert\CommandeStatutConstraint]
class Commande
{
    const STATUT_EN_COURS = 'encours';
    const STATUT_LIVRE = 'livre';
    const STATUT_PAYE = 'paye';
    const STATUT_ANNULE = 'annule';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeInterface $date;

    #[ORM\Column]
    private float $montant;

    #[ORM\Column]
    private \DateTimeInterface $dateLivraisonPrevue;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeInterface $dateLivraisonReelle = null;

    #[ORM\Column(length: 20)]
    private string $statut = self::STATUT_EN_COURS;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private Fournisseur $fournisseur;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeProduit::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $commandeProduits;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Versement::class)]
    private Collection $versements;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->date = new \DateTime();
    }

    // Getters et setters...

    public function getMontantRestant(): float
    {
        $totalVersements = 0;
        foreach ($this->versements as $versement) {
            $totalVersements += $versement->getMontant();
        }
        return $this->montant - $totalVersements;
    }
    public function getId(): ?int
    {
        return $this->id;
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
    public function getDateLivraisonPrevue(): ?\DateTimeInterface
    {
        return $this->dateLivraisonPrevue;
    }
    public function setDateLivraisonPrevue(\DateTimeInterface $dateLivraisonPrevue): self
    {
        $this->dateLivraisonPrevue = $dateLivraisonPrevue;
        return $this;
    }
    public function getDateLivraisonReelle(): ?\DateTimeInterface
    {
        return $this->dateLivraisonReelle;
    }
    public function setDateLivraisonReelle(?\DateTimeInterface $dateLivraisonReelle): self
    {
        $this->dateLivraisonReelle = $dateLivraisonReelle;
        return $this;
    }
    public function getStatut(): ?string
    {
        return $this->statut;
    }
    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }
    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;
        return $this;
    }   
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }
    
    public function getVersements(): Collection
    {
        return $this->versements;
    }
    public function setVersements(Collection $versements): self
    {
        $this->versements = $versements;
        return $this;
    }
    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->add($commandeProduit);
            $commandeProduit->setCommande($this);
        }
        return $this;
    }
    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);    
            }
        }
        return $this;
    }
}
