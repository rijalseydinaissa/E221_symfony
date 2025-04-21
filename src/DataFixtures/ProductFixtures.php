<?php
namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $subCategories = [
            'Clous', 'Vis', 'Marteaux', 'Tournevis', 
            'Pots', 'Rouleaux', 'Interrupteurs', 'Ampoules'
        ];

        for ($i = 1; $i <= 50; $i++) {
            $subCatRef = $subCategories[array_rand($subCategories)];
            $product = new Produit();
            $product->setCode('PROD-'.str_pad($i, 4, '0', STR_PAD_LEFT))
                    ->setDesignation($faker->sentence(3))
                    ->setQuantiteStock($faker->numberBetween(10, 200))
                    ->setPrixUnitaire($faker->randomFloat(2, 1, 100))
                    ->setSousCategorie($this->getReference('subcat_'.$subCatRef, SousCategorie::class));
            
            $manager->persist($product);
            $this->addReference('product_'.$i, $product);
        }

        $manager->flush();
    }
}