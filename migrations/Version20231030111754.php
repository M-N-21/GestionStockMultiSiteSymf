<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030111754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gestionnaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(100) NOT NULL, date DATE DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, telephone VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasinier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(100) NOT NULL, date DATE DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, adresse VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entree ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_598377A6F347EFB ON entree (produit_id)');
        $this->addSql('ALTER TABLE magasin ADD magasinier_id INT NOT NULL');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F27498A9192 FOREIGN KEY (magasinier_id) REFERENCES magasinier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54AF5F27498A9192 ON magasin (magasinier_id)');
        $this->addSql('ALTER TABLE produit ADD gestionnaire_id INT DEFAULT NULL, ADD magasinier_id INT DEFAULT NULL, ADD categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27498A9192 FOREIGN KEY (magasinier_id) REFERENCES magasinier (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC276885AC1B ON produit (gestionnaire_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27498A9192 ON produit (magasinier_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('ALTER TABLE sortie ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2F347EFB ON sortie (produit_id)');
        $this->addSql('ALTER TABLE transfert_stock ADD gestionnaire_id INT NOT NULL, ADD produit_id INT NOT NULL, ADD magasin_origine_id INT NOT NULL, ADD magasin_destination_id INT NOT NULL');
        $this->addSql('ALTER TABLE transfert_stock ADD CONSTRAINT FK_69A78AA96885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('ALTER TABLE transfert_stock ADD CONSTRAINT FK_69A78AA9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE transfert_stock ADD CONSTRAINT FK_69A78AA96E059E66 FOREIGN KEY (magasin_origine_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE transfert_stock ADD CONSTRAINT FK_69A78AA93457A884 FOREIGN KEY (magasin_destination_id) REFERENCES magasin (id)');
        $this->addSql('CREATE INDEX IDX_69A78AA96885AC1B ON transfert_stock (gestionnaire_id)');
        $this->addSql('CREATE INDEX IDX_69A78AA9F347EFB ON transfert_stock (produit_id)');
        $this->addSql('CREATE INDEX IDX_69A78AA96E059E66 ON transfert_stock (magasin_origine_id)');
        $this->addSql('CREATE INDEX IDX_69A78AA93457A884 ON transfert_stock (magasin_destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276885AC1B');
        $this->addSql('ALTER TABLE transfert_stock DROP FOREIGN KEY FK_69A78AA96885AC1B');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F27498A9192');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27498A9192');
        $this->addSql('DROP TABLE gestionnaire');
        $this->addSql('DROP TABLE magasinier');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A6F347EFB');
        $this->addSql('DROP INDEX IDX_598377A6F347EFB ON entree');
        $this->addSql('ALTER TABLE entree DROP produit_id');
        $this->addSql('DROP INDEX UNIQ_54AF5F27498A9192 ON magasin');
        $this->addSql('ALTER TABLE magasin DROP magasinier_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP INDEX IDX_29A5EC276885AC1B ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC27498A9192 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
        $this->addSql('ALTER TABLE produit DROP gestionnaire_id, DROP magasinier_id, DROP categorie_id');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2F347EFB');
        $this->addSql('DROP INDEX IDX_3C3FD3F2F347EFB ON sortie');
        $this->addSql('ALTER TABLE sortie DROP produit_id');
        $this->addSql('ALTER TABLE transfert_stock DROP FOREIGN KEY FK_69A78AA9F347EFB');
        $this->addSql('ALTER TABLE transfert_stock DROP FOREIGN KEY FK_69A78AA96E059E66');
        $this->addSql('ALTER TABLE transfert_stock DROP FOREIGN KEY FK_69A78AA93457A884');
        $this->addSql('DROP INDEX IDX_69A78AA96885AC1B ON transfert_stock');
        $this->addSql('DROP INDEX IDX_69A78AA9F347EFB ON transfert_stock');
        $this->addSql('DROP INDEX IDX_69A78AA96E059E66 ON transfert_stock');
        $this->addSql('DROP INDEX IDX_69A78AA93457A884 ON transfert_stock');
        $this->addSql('ALTER TABLE transfert_stock DROP gestionnaire_id, DROP produit_id, DROP magasin_origine_id, DROP magasin_destination_id');
    }
}
