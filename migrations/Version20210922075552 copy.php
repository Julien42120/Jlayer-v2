<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922075552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichiers ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE fichiers ADD CONSTRAINT FK_969DB4AB12469DE2 FOREIGN KEY (category_id) REFERENCES category_fichier (id)');
        $this->addSql('CREATE INDEX IDX_969DB4AB12469DE2 ON fichiers (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichiers DROP FOREIGN KEY FK_969DB4AB12469DE2');
        $this->addSql('DROP INDEX IDX_969DB4AB12469DE2 ON fichiers');
        $this->addSql('ALTER TABLE fichiers DROP category_id');
    }
}
