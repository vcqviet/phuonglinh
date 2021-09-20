<?php
namespace HK\CoreBundle\Master;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use HK\CoreBundle\Entity\SettingWebsiteOption;
use HK\CoreBundle\Entity\SettingWebsiteCategory;
use HK\CoreBundle\Entity\SettingWebsite;

class MasterMigration extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $entityManager;

    protected $repoCate;

    protected $repoSetting;

    protected $repoOption;

    public function getDescription(): string
    {
        return '';
    }

    protected function addSetting($cate, $name, $key, $type, $noted = '', $order = 0)
    {
        $setting = new SettingWebsite();
        $setting->setCate($cate);
        $setting->setName($name);
        $setting->setNameKey($key);
        $setting->setType($type);
        $setting->setIsPublished(true);
        $setting->setNoted($noted);
        $setting->setDisplayOrder($order);

        $this->repoSetting->saveEntity($setting);
        return $setting;
    }

    protected function addSettingOption($setting, $name, $value, $isDefault = false, $order = 0)
    {
        $option = new SettingWebsiteOption();
        $option->setSetting($setting);
        $option->setName($name);
        $option->setValue($value);
        $option->setIsDefault($isDefault);
        $option->setIsPublished(true);
        $option->setDisplayOrder($order);

        $this->repoOption->saveEntity($option);
        return $option;
    }

    protected function addSettingCate($name, $type, $order = 0)
    {
        $cate = new SettingWebsiteCategory();
        $cate->setName($name);
        $cate->setType($type);
        $cate->setIsPublished(true);
        $cate->setDisplayOrder($order);

        $this->repoCate->saveEntity($cate);
        return $cate;
    }

    public function up(Schema $schema): void
    {}

    public function preUp(Schema $schema): void
    {
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        $this->repoCate = $this->entityManager->getRepository(SettingWebsiteCategory::class);
        $this->repoOption = $this->entityManager->getRepository(SettingWebsiteOption::class);
        $this->repoSetting = $this->entityManager->getRepository(SettingWebsite::class);
        
    }

    public function preDown(Schema $schema): void
    {
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        $this->repoCate = $this->entityManager->getRepository(SettingWebsiteCategory::class);
        $this->repoOption = $this->entityManager->getRepository(SettingWebsiteOption::class);
        $this->repoSetting = $this->entityManager->getRepository(SettingWebsite::class);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    public function __construct($version)
    {
        parent::__construct($version);
    }
}
