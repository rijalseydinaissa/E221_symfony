<?php
namespace App\DataFixtures;

use App\Entity\Versement;
use App\Entity\Commande;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // On suppose que les commandes 1 à 10 existent
        for ($i = 1; $i <= 10; $i++) {
            $payment = new Versement();
            $payment->setNumero('PAY-'.str_pad($i, 5, '0', STR_PAD_LEFT))
                   ->setMontant(rand(50, 500))
                   ->setCommande($this->getReference('order_'.$i, Commande::class));
            
            $manager->persist($payment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CommandesFixtures::class,
        ];
    }
}