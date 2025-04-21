<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/categorie')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->json([
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'categorie_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);
        $categorie->setDescription($data['description'] ?? null);
        
        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->json($categorie, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->json($categorie);
    }

    #[Route('/{id}/edit', name: 'categorie_edit', methods: ['PUT'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $categorie->setNom($data['nom']);
        $categorie->setDescription($data['description'] ?? null);
        
        $entityManager->flush();

        return $this->json($categorie);
    }

    #[Route('/{id}', name: 'categorie_delete', methods: ['DELETE'])]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $categorie->setIsArchived(true);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}