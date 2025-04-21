<?php
namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\Versement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;

class CommandesFixtures extends Fixture implements DependentFixtureInterface
{
    // Dans CommandesFixtures.php
public function load(ObjectManager $manager): void
{
    try {
        $statuses = [
            Commande::STATUT_EN_COURS,
            Commande::STATUT_LIVRE, 
            Commande::STATUT_PAYE,
            Commande::STATUT_ANNULE
        ];

        // Votre code existant...
        
        for ($i = 1; $i <= 20; $i++) {
            echo "Création commande $i\n";
            
            $order = new Commande();
    

            // Créez les dates avant de les utiliser
            $date = new \DateTime('2025-04-21');
            $dateLivraison = new \DateTime('2025-04-21');

            $order->setFournisseur($this->getReference('supplier_'.rand(1, 5), Fournisseur::class))
                  ->setDate($date)
                  ->setDateLivraisonPrevue($dateLivraison )
                  ->setStatut($statuses[array_rand($statuses)]);
            // $order->setVersements(new ArrayCollection());
            echo "Ajout des produits pour commande $i\n";
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
            
            echo "Référence pour commande $i ajoutée\n";
            // Ajouter une référence pour chaque commande
            $this->addReference('order_'.$i, $order);
        }
        
        echo "Flush du manager\n";
        $manager->flush();
        echo "Fixtures chargées avec succès\n";
        
    } catch (\Exception $e) {
        echo "Erreur: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " line " . $e->getLine() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
        throw $e;
    }
}

    public function getDependencies():array
    {
        return [
            ProductFixtures::class,
            SupplierFixtures::class,
        ];
    }
}