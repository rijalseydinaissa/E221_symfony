<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\VersementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/statistique')]
#[IsGranted('ROLE_RESPONSABLE_PAIEMENT')]
class StatistiqueController extends AbstractController
{
    #[Route('/', name: 'statistique_index', methods: ['GET'])]
    public function index(
        CommandeRepository $commandeRepository,
        VersementRepository $versementRepository
    ): Response {
        $commandesEnCours = $commandeRepository->findBy(['statut' => Commande::STATUT_EN_COURS]);
        
        $today = new \DateTime();
        $commandesALivrerAujourdhui = $commandeRepository->findByDateLivraisonPrevue($today);
        
        $detteTotale = 0;
        $commandesNonPayees = $commandeRepository->findCommandesNonPayees();
        foreach ($commandesNonPayees as $commande) {
            $detteTotale += $commande->getMontantRestant();
        }
        
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $versementsDuJour = $versementRepository->findByDate($today);
        
        return $this->json([
            'commandes_en_cours' => $commandesEnCours,
            'commandes_a_livrer_aujourdhui' => $commandesALivrerAujourdhui,
            'dette_totale' => $detteTotale,
            'versements_du_jour' => $versementsDuJour,
        ]);
    }
}