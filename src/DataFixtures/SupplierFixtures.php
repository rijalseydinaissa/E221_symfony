<?php 
namespace App\DataFixtures;

use App\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SupplierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for ($i = 1; $i <= 5; $i++) {
            $supplier = new Fournisseur();
            $supplier->setNom($faker->company)
                    ->setAdresse($faker->address)
                    ->setNumero('FOURN-'.str_pad($i, 3, '0', STR_PAD_LEFT));
            
            $manager->persist($supplier);
            $this->addReference('supplier_'.$i, $supplier);
        }

        $manager->flush();
    }

}