<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260207210652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE case_updates (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, case_id INT NOT NULL, INDEX IDX_EAEBE63BCF10D4F5 (case_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, case_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_5F9E962ACF10D4F5 (case_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE case_updates ADD CONSTRAINT FK_EAEBE63BCF10D4F5 FOREIGN KEY (case_id) REFERENCES cases (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ACF10D4F5 FOREIGN KEY (case_id) REFERENCES cases (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE case_updates DROP FOREIGN KEY FK_EAEBE63BCF10D4F5');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ACF10D4F5');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('DROP TABLE case_updates');
        $this->addSql('DROP TABLE comments');
    }
}
