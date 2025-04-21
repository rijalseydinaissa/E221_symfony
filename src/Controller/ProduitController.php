<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/produit')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->json([
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'produit_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $produit = new Produit();
        $produit->setCode($data['code']);
        $produit->setDesignation($data['designation']);
        $produit->setQuantiteStock($data['quantite_stock']);
        $produit->setPrixUnitaire($data['prix_unitaire']);
        
        $sousCategorie = $entityManager->getRepository(SousCategorie::class)->find($data['sous_categorie_id']);
        $produit->setSousCategorie($sousCategorie);
        
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->json($produit, Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'produit_edit', methods: ['PUT'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $produit->setCode($data['code']);
        $produit->setDesignation($data['designation']);
        $produit->setQuantiteStock($data['quantite_stock']);
        $produit->setPrixUnitaire($data['prix_unitaire']);
        
        $sousCategorie = $entityManager->getRepository(SousCategorie::class)->find($data['sous_categorie_id']);
        $produit->setSousCategorie($sousCategorie);
        
        $entityManager->flush();

        return $this->json($produit);
    }

    #[Route('/{id}', name: 'produit_delete', methods: ['DELETE'])]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $produit->setIsArchived(true);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}