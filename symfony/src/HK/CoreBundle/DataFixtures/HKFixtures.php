<?php

namespace HK\CoreBundle\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use HK\CoreBundle\Entity\CmsRole;
use HK\CoreBundle\Entity\CmsUser;
use HK\CoreBundle\Entity\SettingWebsite;
use HK\CoreBundle\Entity\SettingWebsiteCategory;
use HK\CoreBundle\Entity\SettingWebsiteOption;

class HKFixtures extends Fixture
{
    private $manager;
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->addCmsUser();
        $this->addSetting();
        $this->manager->flush();
    }

    private function addCmsUser()
    {

        $userRole = new CmsRole();
        $userRole->setRoleName(CmsRole::$_ROLE_ADMIN);
        $userRole->setDisplayOrder(0);
        $userRole->setIsDeleted(0);
        $userRole->setCreatedBy('ROOT');
        $userRole->setCreatedAt(new DateTime());
        $userRole->setIsPublished(1);
        $this->manager->persist($userRole);

        $user = new CmsUser();
        $user->setEmailAddress('admin@demo.com');
        $user->setPhoneNumber('0919989274'); //demo
        $user->setLoginPassword('$2a$11$GS0Iw9HIAWMagnj0XBkMGuHuTlZxzU2Rg1nuxK8aszBbAdkyItImO');
        $user->setLoginRan('bi@z');
        $user->setDisplayOrder(0);
        $user->setIsDeleted(0);
        $user->setCreatedBy('ROOT');
        $user->setCreatedAt(new DateTime());
        $user->setIsPublished(1);
        $user->addCmsRole($userRole);
        $this->manager->persist($user);
    }
    private function addSetting()
    {
        $settingCate = new SettingWebsiteCategory();
        $settingCate->setType(SettingWebsiteCategory::$_TYPE_GENERAL);
        $settingCate->setName('setting-email');
        $settingCate->setDisplayOrder(0);
        $settingCate->setIsDeleted(0);
        $settingCate->setCreatedAt(new DateTime());
        $settingCate->setCreatedBy('ROOT');
        $settingCate->setIsPublished(1);
        $this->manager->persist($settingCate);

        $setting = new SettingWebsite();
        $setting->setCate($settingCate);
        $setting->setValue('95f53d4202237b');
        $setting->setType(SettingWebsite::$_TYPE_TEXT);
        $setting->setNameKey(SettingWebsite::$_KEY_SMTP_USER);
        $setting->setName('setting-website.smtp-user');
        $setting->setNoted('setting-website.smtp-user-noted');
        $setting->setDisplayOrder(0);
        $setting->setIsDeleted(0);
        $setting->setCreatedAt(new DateTime());
        $setting->setCreatedBy('ROOT');
        $setting->setIsPublished(1);
        $this->manager->persist($setting);

        $setting = new SettingWebsite();
        $setting->setCate($settingCate);
        $setting->setValue('cd6c131f3250ef');
        $setting->setType(SettingWebsite::$_TYPE_PASSWORD);
        $setting->setNameKey(SettingWebsite::$_KEY_SMTP_PASSWORD);
        $setting->setName('setting-website.smtp-password');
        $setting->setNoted('setting-website.smtp-password-noted');
        $setting->setDisplayOrder(2);
        $setting->setIsDeleted(0);
        $setting->setCreatedAt(new DateTime());
        $setting->setCreatedBy('ROOT');
        $setting->setIsPublished(1);
        $this->manager->persist($setting);

        $setting = new SettingWebsite();
        $setting->setCate($settingCate);
        $setting->setValue('smtp.mailtrap.io');
        $setting->setType(SettingWebsite::$_TYPE_TEXT);
        $setting->setNameKey(SettingWebsite::$_KEY_SMTP_HOST);
        $setting->setName('setting-website.smtp-host');
        $setting->setNoted('setting-website.smtp-host-noted');
        $setting->setDisplayOrder(3);
        $setting->setIsDeleted(0);
        $setting->setCreatedAt(new DateTime());
        $setting->setCreatedBy('ROOT');
        $setting->setIsPublished(1);
        $this->manager->persist($setting);

        $setting = new SettingWebsite();
        $setting->setCate($settingCate);
        $setting->setValue('2525');
        $setting->setType(SettingWebsite::$_TYPE_TEXT);
        $setting->setNameKey(SettingWebsite::$_KEY_SMTP_PORT);
        $setting->setName('setting-website.smtp-port');
        $setting->setNoted('setting-website.smtp-port-noted');
        $setting->setDisplayOrder(4);
        $setting->setIsDeleted(0);
        $setting->setCreatedAt(new DateTime());
        $setting->setCreatedBy('ROOT');
        $setting->setIsPublished(1);
        $this->manager->persist($setting);

        $setting = new SettingWebsite();
        $setting->setCate($settingCate);
        $setting->setValue('tls');
        $setting->setType(SettingWebsite::$_TYPE_RADIO);
        $setting->setNameKey(SettingWebsite::$_KEY_SMTP_TYPE);
        $setting->setName('setting-website.smtp-type');
        $setting->setNoted('setting-website.smtp-type-noted');
        $setting->setDisplayOrder(4);
        $setting->setIsDeleted(0);
        $setting->setCreatedAt(new DateTime());
        $setting->setCreatedBy('ROOT');
        $setting->setIsPublished(1);
        $this->manager->persist($setting);

        $option = new SettingWebsiteOption();
        $option->setSetting($setting);
        $option->setIsDefault(1);
        $option->setValue('ssl');
        $option->setName('setting-website.smtp-type-ssl');
        $option->setDisplayOrder(5);
        $option->setIsDeleted(0);
        $option->setCreatedAt(new DateTime());
        $option->setCreatedBy('ROOT');
        $option->setIsPublished(1);
        $this->manager->persist($option);

        $option = new SettingWebsiteOption();
        $option->setSetting($setting);
        $option->setIsDefault(0);
        $option->setValue('tls');
        $option->setName('setting-website.smtp-type-tls');
        $option->setDisplayOrder(5);
        $option->setIsDeleted(0);
        $option->setCreatedAt(new DateTime());
        $option->setCreatedBy('ROOT');
        $option->setIsPublished(1);
        $this->manager->persist($option);

        $option = new SettingWebsiteOption();
        $option->setSetting($setting);
        $option->setIsDefault(0);
        $option->setValue('');
        $option->setName('setting-website.smtp-type-none');
        $option->setDisplayOrder(5);
        $option->setIsDeleted(0);
        $option->setCreatedAt(new DateTime());
        $option->setCreatedBy('ROOT');
        $option->setIsPublished(1);
        $this->manager->persist($option);
    }
}
