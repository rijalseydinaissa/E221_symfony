<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Création d'un gestionnaire
        $gestionnaire = new User();
        $gestionnaire->setEmail('gestionnaire@gestionnare.com');
        $gestionnaire->setRoles([User::ROLE_GESTIONNAIRE]);
        $gestionnaire->setPassword(
            $this->passwordHasher->hashPassword($gestionnaire, 'password123')
        );
        $manager->persist($gestionnaire);

        // Création d'un responsable achat
        $respAchat = new User();
        $respAchat->setEmail('achat@achat.com');
        $respAchat->setRoles([User::ROLE_RESPONSABLE_ACHAT]);
        $respAchat->setPassword(
            $this->passwordHasher->hashPassword($respAchat, 'password123')
        );
        $manager->persist($respAchat);

        // Création d'un responsable paiement
        $respPaiement = new User();
        $respPaiement->setEmail('paiement@paiement.com');
        $respPaiement->setRoles([User::ROLE_RESPONSABLE_PAIEMENT]);
        $respPaiement->setPassword(
            $this->passwordHasher->hashPassword($respPaiement, 'password123')
        );
        $manager->persist($respPaiement);

        $manager->flush();
    }
}