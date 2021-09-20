<?php
namespace HK\CoreBundle\Configuration;

use Symfony\Component\Yaml\Yaml;
use HK\CoreBundle\Helper\SessionHelper;

class Configuration
{

    private static $_SESSION_LANG = 'hk-lang-multiple';

    private static $instance = null;

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/config.yaml';
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Configuration();
        }
        return self::$instance;
    }

    private function isValidLanguage($lang = ''): bool
    {
        if (empty($lang)) {
            return false;
        }
        $langs = $this->getAllLanguages();
        foreach ($langs as $l) {
            if ($lang === $l) {
                return true;
            }
        }
        return false;
    }

    private function getConfig(): array
    {
        $config = Yaml::parseFile($this->file);
        return $config;
    }

    public function getLanguage($lang = ''): string
    {
        return $this->isValidLanguage($lang) ? $lang : $this->getCurrentLang();
    }

    public function getAllLanguages(): array
    {
        $config = $this->getConfig();
        return isset($config['languages']) ? $config['languages']['all'] : [];
    }

    public function setCurrentLang($lang): string
    {
        $lang = $this->getLanguage($lang);
        SessionHelper::instance()->set(self::$_SESSION_LANG, $lang);
        return $lang;
    }

    public function getCurrentLang(): string
    {
        $lang = SessionHelper::instance()->get(self::$_SESSION_LANG, '');
        return $this->isValidLanguage($lang) ? $lang : $this->getDefaultLanguage();
    }

    public function getDefaultLanguage(): string
    {
        $config = $this->getConfig();
        return $config['languages']['default'];
    }
}