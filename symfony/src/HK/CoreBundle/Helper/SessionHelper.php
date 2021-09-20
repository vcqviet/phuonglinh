<?php
namespace HK\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionHelper
{

    private static $instance = null;

    private $_PREFIX = 'rb-hk-';

    private $session = null;

    public function __construct()
    {
        $this->session = new Session();
    }
 
    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new SessionHelper();
        }
        return self::$instance;
    }

    public function get($key, $default = ''): string
    {
        return strval($this->session->get($this->_PREFIX . $key, $default));
    }

    public function set($key, $value = ''): self
    {
        $this->session->set($this->_PREFIX . $key, $value);
        
        return $this;
    }
}