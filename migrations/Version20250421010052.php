<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421010052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_archived BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande (id SERIAL NOT NULL, fournisseur_id INT NOT NULL, date VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_livraison_prevue VARCHAR(255) NOT NULL, date_livraison_reelle VARCHAR(255) DEFAULT NULL, statut VARCHAR(20) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6EEAA67D670C757F ON commande (fournisseur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande_produit (id SERIAL NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, prix_achat DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DF1E9E8782EA2E54 ON commande_produit (commande_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DF1E9E87F347EFB ON commande_produit (produit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE fournisseur (id SERIAL NOT NULL, numero VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, is_archived BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_369ECA32F55AE19E ON fournisseur (numero)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit (id SERIAL NOT NULL, sous_categorie_id INT NOT NULL, code VARCHAR(50) NOT NULL, designation VARCHAR(255) NOT NULL, quantite_stock INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, is_archived BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_29A5EC2777153098 ON produit (code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29A5EC27365BF48 ON produit (sous_categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sous_categorie (id SERIAL NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, is_archived BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE versement (id SERIAL NOT NULL, commande_id INT NOT NULL, numero VARCHAR(50) NOT NULL, date VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_716E9367F55AE19E ON versement (numero)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_716E936782EA2E54 ON versement (commande_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE versement ADD CONSTRAINT FK_716E936782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP CONSTRAINT FK_6EEAA67D670C757F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP CONSTRAINT FK_DF1E9E8782EA2E54
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP CONSTRAINT FK_DF1E9E87F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27365BF48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sous_categorie DROP CONSTRAINT FK_52743D7BBCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE versement DROP CONSTRAINT FK_716E936782EA2E54
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande_produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE fournisseur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE versement
        SQL);
    }
}
