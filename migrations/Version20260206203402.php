<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206203402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE case_views (id INT AUTO_INCREMENT NOT NULL, ip_hash VARCHAR(64) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, viewed_at DATETIME NOT NULL, case_id INT NOT NULL, viewer_id INT DEFAULT NULL, INDEX IDX_4319666BCF10D4F5 (case_id), INDEX IDX_4319666B6C59C752 (viewer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE case_views ADD CONSTRAINT FK_4319666BCF10D4F5 FOREIGN KEY (case_id) REFERENCES cases (id)');
        $this->addSql('ALTER TABLE case_views ADD CONSTRAINT FK_4319666B6C59C752 FOREIGN KEY (viewer_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE case_views DROP FOREIGN KEY FK_4319666BCF10D4F5');
        $this->addSql('ALTER TABLE case_views DROP FOREIGN KEY FK_4319666B6C59C752');
        $this->addSql('DROP TABLE case_views');
    }
}
