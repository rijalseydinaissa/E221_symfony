<?php
namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Quincaillerie' => 'Tous les articles de quincaillerie générale',
            'Outillage' => 'Outils manuels et électroportatifs',
            'Peinture' => 'Peintures et accessoires',
            'Électricité' => 'Matériel électrique'
        ];

        foreach ($categories as $name => $desc) {
            $category = new Categorie();
            $category->setNom($name)
                    ->setDescription($desc);
            
            $manager->persist($category);
            $this->addReference('cat_'.$name, $category);
        }

        $manager->flush();
    }
}