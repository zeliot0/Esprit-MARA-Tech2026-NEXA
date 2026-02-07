<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206201305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cases (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(180) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(120) DEFAULT NULL, urgency VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, cha9a9a_url VARCHAR(500) NOT NULL, target_amount NUMERIC(12, 2) DEFAULT NULL, current_amount NUMERIC(12, 2) NOT NULL, views_count INT NOT NULL, is_featured TINYINT NOT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, category_id INT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_1C1B038B12469DE2 (category_id), INDEX IDX_1C1B038BB03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(120) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, password_hash VARCHAR(255) NOT NULL, role VARCHAR(20) NOT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT FK_1C1B038B12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT FK_1C1B038BB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY FK_1C1B038B12469DE2');
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY FK_1C1B038BB03A8386');
        $this->addSql('DROP TABLE cases');
        $this->addSql('DROP TABLE users');
    }
}
