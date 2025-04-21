<?php
namespace App\DataFixtures;

use App\Entity\SousCategorie;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subCategories = [
            'Quincaillerie' => ['Clous', 'Vis', 'Écrous', 'Boulons'],
            'Outillage' => ['Marteaux', 'Tournevis', 'Pinces', 'Clés'],
            'Peinture' => ['Pots', 'Rouleaux', 'Pinceaux', 'Baches'],
            'Électricité' => ['Interrupteurs', 'Prises', 'Câbles', 'Ampoules']
        ];

        foreach ($subCategories as $catName => $subs) {
            foreach ($subs as $subName) {
                $subCat = new SousCategorie();
                $subCat->setNom($subName)
                       ->setCategorie($this->getReference('cat_'.$catName, Categorie::class));
                
                $manager->persist($subCat);
                $this->addReference('subcat_'.$subName, $subCat);
            }
        }

        $manager->flush();
    }
}