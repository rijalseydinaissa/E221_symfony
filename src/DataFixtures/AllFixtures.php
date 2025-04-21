<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AllFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager):void
    {
        // Vide - sert juste à gérer les dépendances
    }

    public function getDependencies():array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            SubCategoryFixtures::class,
            SupplierFixtures::class,
            ProductFixtures::class,
            CommandesFixtures::class,
            PaymentFixtures::class,
        ];
    }
}