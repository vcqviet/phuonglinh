<?php

declare(strict_types=1);

namespace HK\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906123746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_2CCD63996B01BC5B ON hkcustomers');
        $this->addSql('DROP INDEX UNIQ_2CCD639976C90985 ON hkcustomers');
        $this->addSql('DROP INDEX UNIQ_2CCD6399B08E074E ON hkcustomers');
        $this->addSql('DROP INDEX UNIQ_2CCD6399DBC463C4 ON hkcustomers');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CCD63996B01BC5B ON hkcustomers (phone_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CCD639976C90985 ON hkcustomers (product_model)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CCD6399B08E074E ON hkcustomers (email_address)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CCD6399DBC463C4 ON hkcustomers (full_name)');
    }
}
