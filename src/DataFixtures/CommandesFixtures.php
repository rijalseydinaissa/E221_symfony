<?php
namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommandesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $statuses = [
            Commande::STATUT_EN_COURS,
            Commande::STATUT_LIVRE, 
            Commande::STATUT_PAYE,
            Commande::STATUT_ANNULE
        ];

        for ($i = 1; $i <= 20; $i++) {
            $order = new Commande();
            $order->setFournisseur($this->getReference('supplier_'.rand(1, 5), Fournisseur::class))
                  ->setDate(new \DateTime('-'.rand(0, 30).' days'))
                  ->setDateLivraisonPrevue(new \DateTime('+'.rand(1, 15).' days'))
                  ->setStatut($statuses[array_rand($statuses)]);
            
            // Ajout de produits à la commande
            $total = 0;
            for ($j = 1; $j <= rand(1, 5); $j++) {
                $product = $this->getReference('product_'.rand(1, 50), Produit::class);
                $orderProduct = new CommandeProduit();
                $orderProduct->setProduit($product)
                            ->setQuantite(rand(1, 20))
                            ->setPrixAchat($product->getPrixUnitaire() * 0.8); // 20% de remise
                
                $order->addCommandeProduit($orderProduct);
                $total += $orderProduct->getQuantite() * $orderProduct->getPrixAchat();
            }
            
            $order->setMontant($total);
            $manager->persist($order);
            
            // Ajouter une référence pour chaque commande
            $this->addReference('order_'.$i, $order);
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            ProductFixtures::class,
            SupplierFixtures::class,
        ];
    }
}