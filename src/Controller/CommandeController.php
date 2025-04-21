<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\CommandeProduit;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
#[IsGranted('ROLE_RESPONSABLE_ACHAT')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->json([
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'commande_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($data['fournisseur_id']);
        $commande->setFournisseur($fournisseur);
        
        $commande->setDateLivraisonPrevue(new \DateTime($data['date_livraison_prevue']));
        
        $montantTotal = 0;
        foreach ($data['produits'] as $produitData) {
            $commandeProduit = new CommandeProduit();
            
            $produit = $entityManager->getRepository(Produit::class)->find($produitData['id']);
            $commandeProduit->setProduit($produit);
            
            $commandeProduit->setQuantite($produitData['quantite']);
            $commandeProduit->setPrixAchat($produitData['prix_achat']);
            
            $commande->addCommandeProduit($commandeProduit);
            
            $montantTotal += $produitData['quantite'] * $produitData['prix_achat'];
        }
        
        $commande->setMontant($montantTotal);
        
        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->json($commande, Response::HTTP_CREATED);
    }

    #[Route('/{id}/annuler', name: 'commande_annuler', methods: ['PUT'])]
    public function annuler(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $commande->setStatut(Commande::STATUT_ANNULE);
        $entityManager->flush();

        return $this->json($commande);
    }

    #[Route('/filter', name: 'commande_filter', methods: ['GET'])]
    public function filter(Request $request, CommandeRepository $commandeRepository): Response
    {
        $statut = $request->query->get('statut');
        $date = $request->query->get('date');
        
        $criteria = [];
        if ($statut) {
            $criteria['statut'] = $statut;
        }
        if ($date) {
            $criteria['date'] = new \DateTime($date);
        }
        
        return $this->json([
            'commandes' => $commandeRepository->findBy($criteria),
        ]);
    }
}