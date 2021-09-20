<?php
namespace HK\CoreBundle\MenuAdmin;

use Symfony\Component\Yaml\Yaml;

class MenuAdmin
{

    public static $_TYPE_MENU = 'menu';

    public static $_TYPE_GROUP = 'group';

    public static $_TYPE_SESSION = 'session';

    public static $_URL_ADMIN_PREFIX = '/admin';

    private static $instance = null;

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/menu.yaml';
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new MenuAdmin();
        }
        return self::$instance;
    }

    public function getMenuAdmin(): array
    {
        $menu = Yaml::parseFile($this->file);
        foreach ($menu as $key => $val) {
            if (! isset($val['type']) || $val['type'] === self::$_TYPE_MENU) {
                $menu[$key]['text'] = $key;
                $menu[$key]['url'] = self::$_URL_ADMIN_PREFIX . $menu[$key]['url'];
                continue;
            }
            if (! isset($val['text'])) {
                $menu[$key]['text'] = $key;
            }
            
            foreach ($val['menu'] as $key2 => $val2) {
                if (! isset($val2['type']) || $val2['type'] === self::$_TYPE_MENU) {
                    $menu[$key]['menu'][$key2]['text'] = $key2;
                    $menu[$key]['menu'][$key2]['url'] = self::$_URL_ADMIN_PREFIX . $menu[$key]['menu'][$key2]['url'];
                    continue;
                }
                if (! isset($val2['text'])) {
                    $menu[$key]['menu'][$key2]['text'] = $key2;
                }
                foreach ($val2['menu'] as $key3 => $val3) {
                    $menu[$key]['menu'][$key2]['menu'][$key3]['text'] = $key3;
                    $menu[$key]['menu'][$key2]['menu'][$key3]['url'] = self::$_URL_ADMIN_PREFIX . $menu[$key]['menu'][$key2]['menu'][$key3]['url'];
                }
            }
        }
        return $menu;
    }
}