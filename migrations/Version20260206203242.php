<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206203242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE case_images (id INT AUTO_INCREMENT NOT NULL, image_url VARCHAR(500) NOT NULL, alt_text VARCHAR(180) DEFAULT NULL, sort_order INT NOT NULL, created_at DATETIME NOT NULL, case_id INT NOT NULL, INDEX IDX_49F1F9C3CF10D4F5 (case_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE case_images ADD CONSTRAINT FK_49F1F9C3CF10D4F5 FOREIGN KEY (case_id) REFERENCES cases (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE case_images DROP FOREIGN KEY FK_49F1F9C3CF10D4F5');
        $this->addSql('DROP TABLE case_images');
    }
}
