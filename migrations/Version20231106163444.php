<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106163444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276885AC1B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27498A9192');
        $this->addSql('DROP INDEX IDX_29A5EC27498A9192 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC276885AC1B ON produit');
        $this->addSql('ALTER TABLE produit ADD user_id INT NOT NULL, DROP gestionnaire_id, DROP magasinier_id');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27A76ED395 ON produit (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A76ED395');
        $this->addSql('DROP INDEX IDX_29A5EC27A76ED395 ON produit');
        $this->addSql('ALTER TABLE produit ADD gestionnaire_id INT DEFAULT NULL, ADD magasinier_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27498A9192 FOREIGN KEY (magasinier_id) REFERENCES magasinier (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27498A9192 ON produit (magasinier_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC276885AC1B ON produit (gestionnaire_id)');
    }
}
