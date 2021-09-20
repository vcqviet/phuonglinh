<?php

declare(strict_types=1);

namespace HK\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404152500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE home_sliders (id INT AUTO_INCREMENT NOT NULL, reference_url VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home_slider_contents (id INT AUTO_INCREMENT NOT NULL, ref_id INT DEFAULT NULL, seo_url VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, lang VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_FCB6A4C121B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE home_slider_contents ADD CONSTRAINT FK_FCB6A4C121B741A9 FOREIGN KEY (ref_id) REFERENCES home_sliders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE home_slider_contents DROP FOREIGN KEY FK_FCB6A4C121B741A9');
        $this->addSql('DROP TABLE home_sliders');
        $this->addSql('DROP TABLE home_slider_contents');
    }
}
