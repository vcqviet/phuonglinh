<?php

declare(strict_types=1);

namespace HK\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905112202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hkcustomers (id INT AUTO_INCREMENT NOT NULL, email_address VARCHAR(255) DEFAULT NULL, address VARCHAR(512) DEFAULT NULL, phone_number VARCHAR(20) NOT NULL, product_model VARCHAR(20) NOT NULL, date_of_birth DATETIME DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2CCD6399B08E074E (email_address), UNIQUE INDEX UNIQ_2CCD63996B01BC5B (phone_number), UNIQUE INDEX UNIQ_2CCD639976C90985 (product_model), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hkcustomers');
    }
}
