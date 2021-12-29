<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228144258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564F915CFE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564F915CFE FOREIGN KEY (fichier_id) REFERENCES fichiers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564F915CFE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564F915CFE FOREIGN KEY (fichier_id) REFERENCES fichiers (id)');
    }
}
