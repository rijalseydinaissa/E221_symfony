<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/fournisseur')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'fournisseur_index', methods: ['GET'])]
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->json([
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'fournisseur_edit', methods: ['PUT'])]
    public function edit(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $fournisseur->setNom($data['nom']);
        $fournisseur->setAdresse($data['adresse']);
        $fournisseur->setNumero($data['numero']);
        $fournisseur->setIsArchived($data['is_archived']);
        
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return $this->json($fournisseur);
    }

    #[Route('/{id}', name: 'fournisseur_delete', methods: ['DELETE'])]
    public function delete(Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $fournisseur->setIsArchived(true);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}