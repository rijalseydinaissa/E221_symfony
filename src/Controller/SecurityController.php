<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(
        Request $request,
        JWTTokenManagerInterface $JWTManager,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
    
        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
    
        $token = $JWTManager->create($user);
    
        return new JsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    private function checkBusinessRoles(UserInterface $user): void
    {
        $allowedRoles = [
            User::ROLE_GESTIONNAIRE,
            User::ROLE_RESPONSABLE_ACHAT,
            User::ROLE_RESPONSABLE_PAIEMENT
        ];

        if (count(array_intersect($allowedRoles, $user->getRoles())) === 0) {
            throw new AuthenticationException('Accès non autorisé à l\'application');
        }
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode doit être interceptée par le firewall');
    }

    #[Route('/api/check-auth', name: 'api_check_auth', methods: ['GET'])]
    public function checkAuth(): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user instanceof User) { // Changez ici aussi
            return new JsonResponse(['authenticated' => false], Response::HTTP_UNAUTHORIZED);
        }
    
        return new JsonResponse([
            'authenticated' => true,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }
}