<?php

namespace App\Controller;

use App\Entity\Versement;
use App\Entity\Commande;
use App\Repository\VersementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/versement')]
#[IsGranted('ROLE_RESPONSABLE_PAIEMENT')]
class VersementController extends AbstractController
{
    #[Route('/commande/{id}', name: 'versement_par_commande', methods: ['GET'])]
    public function parCommande(Commande $commande): Response
    {
        return $this->json([
            'versements' => $commande->getVersements(),
            'montant_restant' => $commande->getMontantRestant(),
        ]);
    }

    #[Route('/new', name: 'versement_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $versement = new Versement();
        $versement->setNumero(uniqid('VERS-'));
        $versement->setMontant($data['montant']);
        
        $commande = $entityManager->getRepository(Commande::class)->find($data['commande_id']);
        $versement->setCommande($commande);
        
        $entityManager->persist($versement);
        $entityManager->flush();

        if ($commande->getMontantRestant() <= 0) {
            $commande->setStatut(Commande::STATUT_PAYE);
            $entityManager->flush();
        }

        return $this->json($versement, Response::HTTP_CREATED);
    }

    #[Route('/today', name: 'versement_today', methods: ['GET'])]
    public function versementsAujourdhui(VersementRepository $versementRepository): Response
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        
        return $this->json([
            'versements' => $versementRepository->findByDate($today),
        ]);
    }
}