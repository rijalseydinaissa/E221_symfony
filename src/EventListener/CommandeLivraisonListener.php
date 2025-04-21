<?php

namespace App\EventListener;

use App\Entity\Commande;
use App\Service\PaiementService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CommandeLivraisonListener
{
    private $paiementService;

    public function __construct(PaiementService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    public function preUpdate(Commande $commande, LifecycleEventArgs $event)
    {
        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet($commande);
        
        if (isset($changeSet['dateLivraisonReelle'])) {
            // La date de livraison réelle a été mise à jour
            $oldValue = $changeSet['dateLivraisonReelle'][0];
            $newValue = $changeSet['dateLivraisonReelle'][1];
            
            if ($oldValue === null && $newValue !== null) {
                // C'est une livraison (passage de null à une date)
                $commande->setStatut(Commande::STATUT_LIVRE);
                
                // Créer les paiements échelonnés
                $this->paiementService->creerPaiementsEchelonnes($commande);
            }
        }
    }
}