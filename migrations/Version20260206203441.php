<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206203441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donations (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(12, 2) NOT NULL, currency VARCHAR(10) NOT NULL, donor_name VARCHAR(120) DEFAULT NULL, donor_email VARCHAR(180) DEFAULT NULL, status VARCHAR(20) NOT NULL, note VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, case_id INT NOT NULL, donor_id INT DEFAULT NULL, INDEX IDX_CDE98962CF10D4F5 (case_id), INDEX IDX_CDE989623DD7B7A7 (donor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE donations ADD CONSTRAINT FK_CDE98962CF10D4F5 FOREIGN KEY (case_id) REFERENCES cases (id)');
        $this->addSql('ALTER TABLE donations ADD CONSTRAINT FK_CDE989623DD7B7A7 FOREIGN KEY (donor_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations DROP FOREIGN KEY FK_CDE98962CF10D4F5');
        $this->addSql('ALTER TABLE donations DROP FOREIGN KEY FK_CDE989623DD7B7A7');
        $this->addSql('DROP TABLE donations');
    }
}
