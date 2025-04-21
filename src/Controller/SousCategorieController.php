<?php

namespace App\Controller;

use App\Entity\SousCategorie;
use App\Entity\Categorie;
use App\Repository\SousCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/sous-categorie')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class SousCategorieController extends AbstractController
{
    #[Route('/', name: 'sous_categorie_index', methods: ['GET'])]
    public function index(SousCategorieRepository $sousCategorieRepository): Response
    {
        return $this->json([
            'sous_categories' => $sousCategorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'sous_categorie_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $sousCategorie = new SousCategorie();
        $sousCategorie->setNom($data['nom']);
        
        $categorie = $entityManager->getRepository(Categorie::class)->find($data['categorie_id']);
        $sousCategorie->setCategorie($categorie);
        
        $entityManager->persist($sousCategorie);
        $entityManager->flush();

        return $this->json($sousCategorie, Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'sous_categorie_edit', methods: ['PUT'])]
    public function edit(Request $request, SousCategorie $sousCategorie, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
    
        $sousCategorie->setNom($data['nom']);
    
        $entityManager->flush();
    
        return $this->json($sousCategorie);
    }
    
    #[Route('/{id}', name: 'sous_categorie_delete', methods: ['DELETE'])]
    public function delete(SousCategorie $sousCategorie, EntityManagerInterface $entityManager): Response
    {
        $sousCategorie->setIsArchived(true);
        $entityManager->flush();
    
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}