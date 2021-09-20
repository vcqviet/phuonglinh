<?php
namespace HK\CoreBundle\Translation;

use Symfony\Component\Yaml\Yaml;
use HK\CoreBundle\Configuration\Configuration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Translation
{

    private static $instance = null;

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/translator.yaml';
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Translation();
        }
        return self::$instance;
    }

    public function getTranslators(): array
    {
        $translators = Yaml::parseFile($this->file);
        return $translators;
    }

    public function getCurrentTranslate($lang, ParameterBagInterface $param)
    {
        $lang = Configuration::instance()->getLanguage($lang);
        return Yaml::parseFile($this->getFileName($lang, $param));
    }

    public function saveCurrentTranslator($lang, $data = [], ParameterBagInterface $param)
    {
        $data = Yaml::dump($data);
        $filename = $this->getFileName($lang, $param);
        $file = fopen($filename, 'w');
        fwrite($file, $data);
        fclose($file);
        $this->removeCache($param);
    }

    private function getFileName($lang = '', ParameterBagInterface $param)
    {
        $lang = Configuration::instance()->getLanguage($lang);
        return $this->getRealPathTranslator($param) . '/messages.' . $lang . '.yml';
    }

    private function removeCache(ParameterBagInterface $param)
    {
        $path = $param->get('kernel.project_dir') . '/../httpdocs/var/' . getenv('APP_ENV') . '/cache/translations';
        array_map('unlink', glob("$path/*"));
    }

    private function getRealPathTranslator(ParameterBagInterface $param): string
    {
        return $param->get('kernel.project_dir') . '/../httpdocs/translations';
    }
}