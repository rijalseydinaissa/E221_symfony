<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423204810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Première étape: supprimer la valeur par défaut
        $this->addSql('ALTER TABLE commande ALTER date_livraison_reelle DROP DEFAULT');
        
        // Deuxième étape: modifier les types
        $this->addSql('ALTER TABLE commande ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING date::TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE commande ALTER date_livraison_prevue TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING date_livraison_prevue::TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE commande ALTER date_livraison_reelle TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING CASE WHEN date_livraison_reelle IS NULL OR date_livraison_reelle = \'\' THEN NULL ELSE date_livraison_reelle::TIMESTAMP(0) WITHOUT TIME ZONE END');
    }
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE versement ALTER date TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP CONSTRAINT fk_df1e9e8782ea2e54
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT fk_df1e9e8782ea2e54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
