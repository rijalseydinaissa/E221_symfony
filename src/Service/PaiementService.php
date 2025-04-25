<?php
// src/Service/PaiementService.php
namespace App\Service;

use App\Entity\Commande;
use App\Entity\Versement;
use Doctrine\ORM\EntityManagerInterface;

class PaiementService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function creerPaiementsEchelonnes(Commande $commande): void
    {
        if ($commande->getDateLivraisonReelle() === null) {
            throw new \LogicException('La livraison doit être effectuée avant de créer les paiements échelonnés');
        }

        $montantVersement = $commande->getMontant() / 3;
        
        // Convertir DateTimeInterface en DateTime
        $dateLivraison = new \DateTime($commande->getDateLivraisonReelle()->format('Y-m-d H:i:s'));

        for ($i = 0; $i < 3; $i++) {
            $versement = new Versement();
            $versement->setCommande($commande);
            $versement->setMontant($montantVersement);
            $versement->setNumero('VERS-'.uniqid());

            // Créer une nouvelle instance de DateTime pour chaque versement
            $datePaiement = new \DateTime($dateLivraison->format('Y-m-d H:i:s'));
            $datePaiement->modify('+' . ($i * 5) . ' days');
            $versement->setDate($datePaiement);

            $this->entityManager->persist($versement);
        }

        $this->entityManager->flush();
    }
}