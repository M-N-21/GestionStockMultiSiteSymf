<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113195621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin ADD gestionnaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F276885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('CREATE INDEX IDX_54AF5F276885AC1B ON magasin (gestionnaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F276885AC1B');
        $this->addSql('DROP INDEX IDX_54AF5F276885AC1B ON magasin');
        $this->addSql('ALTER TABLE magasin DROP gestionnaire_id');
    }
}
