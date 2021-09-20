<?php

declare(strict_types=1);

namespace HK\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404150233 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hknews (id INT AUTO_INCREMENT NOT NULL, cate_id INT DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, INDEX IDX_87102C847D3008E5 (cate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hknews_categories (id INT AUTO_INCREMENT NOT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hknews_category_contents (id INT AUTO_INCREMENT NOT NULL, ref_id INT DEFAULT NULL, seo_url VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, lang VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_E2FC89FF21B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hknews_contents (id INT AUTO_INCREMENT NOT NULL, ref_id INT DEFAULT NULL, seo_url VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, lang VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_6B31CAE321B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hknews ADD CONSTRAINT FK_87102C847D3008E5 FOREIGN KEY (cate_id) REFERENCES hknews_categories (id)');
        $this->addSql('ALTER TABLE hknews_category_contents ADD CONSTRAINT FK_E2FC89FF21B741A9 FOREIGN KEY (ref_id) REFERENCES hknews_categories (id)');
        $this->addSql('ALTER TABLE hknews_contents ADD CONSTRAINT FK_6B31CAE321B741A9 FOREIGN KEY (ref_id) REFERENCES hknews (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hknews_contents DROP FOREIGN KEY FK_6B31CAE321B741A9');
        $this->addSql('ALTER TABLE hknews DROP FOREIGN KEY FK_87102C847D3008E5');
        $this->addSql('ALTER TABLE hknews_category_contents DROP FOREIGN KEY FK_E2FC89FF21B741A9');
        $this->addSql('DROP TABLE hknews');
        $this->addSql('DROP TABLE hknews_categories');
        $this->addSql('DROP TABLE hknews_category_contents');
        $this->addSql('DROP TABLE hknews_contents');
    }
}
