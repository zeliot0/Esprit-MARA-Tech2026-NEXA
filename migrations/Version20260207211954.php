<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260207211954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_comments (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_2BC3B20D4B89032C (post_id), INDEX IDX_2BC3B20DF675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D4B89032C FOREIGN KEY (post_id) REFERENCES blog_posts (id)');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20DF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_comments DROP FOREIGN KEY FK_2BC3B20D4B89032C');
        $this->addSql('ALTER TABLE blog_comments DROP FOREIGN KEY FK_2BC3B20DF675F31B');
        $this->addSql('DROP TABLE blog_comments');
    }
}
