<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113194011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasinier ADD gestionnaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE magasinier ADD CONSTRAINT FK_588E099C6885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('CREATE INDEX IDX_588E099C6885AC1B ON magasinier (gestionnaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasinier DROP FOREIGN KEY FK_588E099C6885AC1B');
        $this->addSql('DROP INDEX IDX_588E099C6885AC1B ON magasinier');
        $this->addSql('ALTER TABLE magasinier DROP gestionnaire_id');
    }
}
