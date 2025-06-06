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
    #[Route('/all', name: 'fournisseur_index', methods: ['GET'])]
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseurs=$fournisseurRepository->findAll();
        $data=array_map( function(Fournisseur $fournisseur){
            return [
                'id'=>$fournisseur->getId(),
                'nom'=>$fournisseur->getNom(),
                'adresse'=>$fournisseur->getAdresse(),
                'isArchived'=>$fournisseur->getIsArchived(),
                'numero'=>$fournisseur->getNumero(),
            ];
        },$fournisseurs);  
        return  $this->json(['fournisseurs'=>$data]);
    }
    #[Route('/new', name: 'fournisseur', methods: ['POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager):Response{
        $data=json_decode( $request->getContent(),true);
        $fournisseur= new Fournisseur();
        $fournisseur->setNom($data['nom']);
        $fournisseur->setAdresse($data['adresse']);
        $fournisseur->setNumero($data['numero']);

        $entityManager->persist($fournisseur);
        $entityManager->flush();
        return $this->json([
            'id'=>$fournisseur->getId(),
            'nom'=>$fournisseur->getNom(),
            'adresse'=>$fournisseur->getAdresse(),
            'isArchived'=>$fournisseur->getIsArchived(),
            'numero'=>$fournisseur->getNumero(),
        ],Response::HTTP_CREATED);

    }
    #[Route('/{id}/edit', name: 'fournisseur_edit', methods: ['PUT'])]
    public function edit(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $fournisseur->setNom($data['nom']);
        $fournisseur->setAdresse($data['adresse']);
        $fournisseur->setNumero($data['numero']);
        $fournisseur->setIsArchived($data['isArchived']);
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return $this->json([
            'id'=>$fournisseur->getId(),
            'nom'=>$fournisseur->getNom(),
            'adresse'=>$fournisseur->getAdresse(),
            'isArchived'=>$fournisseur->getIsArchived(),
            'numero'=>$fournisseur->getNumero(),
        ]);
    }

    #[Route('/{id}', name: 'fournisseur_delete', methods: ['DELETE'])]
    public function delete(Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $fournisseur->setIsArchived(true);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}