<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206195758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE audit_logs DROP FOREIGN KEY `fk_audit_user`');
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY `fk_cases_category`');
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY `fk_cases_user`');
        $this->addSql('ALTER TABLE case_images DROP FOREIGN KEY `fk_case_images_case`');
        $this->addSql('ALTER TABLE case_views DROP FOREIGN KEY `fk_views_case`');
        $this->addSql('ALTER TABLE case_views DROP FOREIGN KEY `fk_views_user`');
        $this->addSql('ALTER TABLE donations DROP FOREIGN KEY `fk_donations_case`');
        $this->addSql('ALTER TABLE donations DROP FOREIGN KEY `fk_donations_user`');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY `fk_events_user`');
        $this->addSql('DROP TABLE audit_logs');
        $this->addSql('DROP TABLE cases');
        $this->addSql('DROP TABLE case_images');
        $this->addSql('DROP TABLE case_views');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE donations');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_logs (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, action VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, entity_type VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, entity_id INT DEFAULT NULL, meta_json JSON DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_audit_user (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cases (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, created_by INT NOT NULL, title VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, location VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, urgency ENUM(\'LOW\', \'MEDIUM\', \'HIGH\', \'CRITICAL\') CHARACTER SET utf8mb4 DEFAULT \'MEDIUM\' NOT NULL COLLATE `utf8mb4_unicode_ci`, status ENUM(\'DRAFT\', \'PUBLISHED\', \'PAUSED\', \'CLOSED\') CHARACTER SET utf8mb4 DEFAULT \'PUBLISHED\' NOT NULL COLLATE `utf8mb4_unicode_ci`, cha9a9a_url VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, target_amount NUMERIC(12, 2) DEFAULT NULL, current_amount NUMERIC(12, 2) DEFAULT \'0.00\' NOT NULL, views_count INT DEFAULT 0 NOT NULL, is_featured TINYINT DEFAULT 0 NOT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX idx_cases_featured (is_featured), INDEX idx_cases_category (category_id), FULLTEXT INDEX ft_cases_search (title, description), INDEX idx_cases_status (status), INDEX idx_cases_urgency (urgency), INDEX fk_cases_user (created_by), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE case_images (id INT AUTO_INCREMENT NOT NULL, case_id INT NOT NULL, image_url VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, alt_text VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sort_order INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX idx_case_images_case (case_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE case_views (id INT AUTO_INCREMENT NOT NULL, case_id INT NOT NULL, viewer_id INT DEFAULT NULL, ip_hash VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, user_agent VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_views_user (viewer_id), INDEX idx_case_views_case (case_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(90) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, icon VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX name (name), UNIQUE INDEX slug (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE donations (id INT AUTO_INCREMENT NOT NULL, case_id INT NOT NULL, donor_id INT DEFAULT NULL, amount NUMERIC(12, 2) DEFAULT \'0.00\' NOT NULL, currency VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'TND\' NOT NULL COLLATE `utf8mb4_unicode_ci`, donor_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, donor_email VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status ENUM(\'PLEDGED\', \'CONFIRMED\', \'CANCELLED\') CHARACTER SET utf8mb4 DEFAULT \'PLEDGED\' NOT NULL COLLATE `utf8mb4_unicode_ci`, note VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX idx_donations_case (case_id), INDEX idx_donations_status (status), INDEX fk_donations_user (donor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, created_by INT NOT NULL, title VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, location VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, start_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, status ENUM(\'PLANNED\', \'ONGOING\', \'DONE\', \'CANCELLED\') CHARACTER SET utf8mb4 DEFAULT \'PLANNED\' NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_events_user (created_by), INDEX idx_events_start (start_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password_hash VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role ENUM(\'ADMIN\', \'ASSOCIATION\', \'DONOR\') CHARACTER SET utf8mb4 DEFAULT \'DONOR\' NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX email (email), INDEX idx_users_role (role), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE audit_logs ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT `fk_cases_category` FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT `fk_cases_user` FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE case_images ADD CONSTRAINT `fk_case_images_case` FOREIGN KEY (case_id) REFERENCES cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE case_views ADD CONSTRAINT `fk_views_case` FOREIGN KEY (case_id) REFERENCES cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE case_views ADD CONSTRAINT `fk_views_user` FOREIGN KEY (viewer_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE donations ADD CONSTRAINT `fk_donations_case` FOREIGN KEY (case_id) REFERENCES cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE donations ADD CONSTRAINT `fk_donations_user` FOREIGN KEY (donor_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT `fk_events_user` FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
