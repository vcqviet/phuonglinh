<?php

declare(strict_types=1);

namespace HK\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404122524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hkabout_pages (id INT AUTO_INCREMENT NOT NULL, name_key VARCHAR(255) DEFAULT NULL, menu_position VARCHAR(255) DEFAULT NULL, is_menu TINYINT(1) NOT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_22CF97F18E85A347 (name_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hkabout_page_contents (id INT AUTO_INCREMENT NOT NULL, ref_id INT DEFAULT NULL, seo_url VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, lang VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_922EECA321B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_ip_locks (id INT AUTO_INCREMENT NOT NULL, ip_locked VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_roles (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(255) NOT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_984DB5EBE09C0C92 (role_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_role_permissions (id INT AUTO_INCREMENT NOT NULL, cms_role_id INT DEFAULT NULL, module_name VARCHAR(255) DEFAULT NULL, url_action VARCHAR(255) DEFAULT NULL, access_right VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, INDEX IDX_8FA5759C88EF2DBD (cms_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_users (id INT AUTO_INCREMENT NOT NULL, email_address VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, login_ran VARCHAR(20) NOT NULL, login_password VARCHAR(255) NOT NULL, recover_time DATETIME DEFAULT NULL, last_logged_in_at DATETIME DEFAULT NULL, last_logged_in_ip VARCHAR(20) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3AF03EC5B08E074E (email_address), UNIQUE INDEX UNIQ_3AF03EC56B01BC5B (phone_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_user_to_cms_roles (cms_user_id INT NOT NULL, cms_role_id INT NOT NULL, INDEX IDX_9962995BF982DC84 (cms_user_id), INDEX IDX_9962995B88EF2DBD (cms_role_id), PRIMARY KEY(cms_user_id, cms_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_user_login_logs (id INT AUTO_INCREMENT NOT NULL, login_at DATETIME DEFAULT NULL, login_ip VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, is_success TINYINT(1) NOT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hksetting_mail_templates (id INT AUTO_INCREMENT NOT NULL, name_key VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, copy_to VARCHAR(255) DEFAULT NULL, attachment VARCHAR(255) DEFAULT NULL, blind_copy_to VARCHAR(255) DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, content TEXT DEFAULT NULL, content_text TEXT DEFAULT NULL, is_stopped TINYINT(1) NOT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1810F0C8E85A347 (name_key), UNIQUE INDEX UNIQ_1810F0CFBCE3E7A (subject), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hksetting_websites (id INT AUTO_INCREMENT NOT NULL, cate_id INT DEFAULT NULL, attribute VARCHAR(512) DEFAULT NULL, noted VARCHAR(255) DEFAULT NULL, value TEXT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, name_key VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, INDEX IDX_36FB4E277D3008E5 (cate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hksetting_website_categories (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hksetting_website_options (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, is_default TINYINT(1) NOT NULL, value VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, display_order INT NOT NULL, view_counter INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_from_at DATETIME DEFAULT NULL, published_to_at DATETIME DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, INDEX IDX_AB0027A1EE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hkabout_page_contents ADD CONSTRAINT FK_922EECA321B741A9 FOREIGN KEY (ref_id) REFERENCES hkabout_pages (id)');
        $this->addSql('ALTER TABLE cms_role_permissions ADD CONSTRAINT FK_8FA5759C88EF2DBD FOREIGN KEY (cms_role_id) REFERENCES cms_roles (id)');
        $this->addSql('ALTER TABLE cms_user_to_cms_roles ADD CONSTRAINT FK_9962995BF982DC84 FOREIGN KEY (cms_user_id) REFERENCES cms_users (id)');
        $this->addSql('ALTER TABLE cms_user_to_cms_roles ADD CONSTRAINT FK_9962995B88EF2DBD FOREIGN KEY (cms_role_id) REFERENCES cms_roles (id)');
        $this->addSql('ALTER TABLE hksetting_websites ADD CONSTRAINT FK_36FB4E277D3008E5 FOREIGN KEY (cate_id) REFERENCES hksetting_website_categories (id)');
        $this->addSql('ALTER TABLE hksetting_website_options ADD CONSTRAINT FK_AB0027A1EE35BD72 FOREIGN KEY (setting_id) REFERENCES hksetting_websites (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hkabout_page_contents DROP FOREIGN KEY FK_922EECA321B741A9');
        $this->addSql('ALTER TABLE cms_role_permissions DROP FOREIGN KEY FK_8FA5759C88EF2DBD');
        $this->addSql('ALTER TABLE cms_user_to_cms_roles DROP FOREIGN KEY FK_9962995B88EF2DBD');
        $this->addSql('ALTER TABLE cms_user_to_cms_roles DROP FOREIGN KEY FK_9962995BF982DC84');
        $this->addSql('ALTER TABLE hksetting_website_options DROP FOREIGN KEY FK_AB0027A1EE35BD72');
        $this->addSql('ALTER TABLE hksetting_websites DROP FOREIGN KEY FK_36FB4E277D3008E5');
        $this->addSql('DROP TABLE hkabout_pages');
        $this->addSql('DROP TABLE hkabout_page_contents');
        $this->addSql('DROP TABLE cms_ip_locks');
        $this->addSql('DROP TABLE cms_roles');
        $this->addSql('DROP TABLE cms_role_permissions');
        $this->addSql('DROP TABLE cms_users');
        $this->addSql('DROP TABLE cms_user_to_cms_roles');
        $this->addSql('DROP TABLE cms_user_login_logs');
        $this->addSql('DROP TABLE hksetting_mail_templates');
        $this->addSql('DROP TABLE hksetting_websites');
        $this->addSql('DROP TABLE hksetting_website_categories');
        $this->addSql('DROP TABLE hksetting_website_options');
    }
}
