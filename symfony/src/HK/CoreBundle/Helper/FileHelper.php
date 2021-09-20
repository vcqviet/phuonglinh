<?php

namespace HK\CoreBundle\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileHelper
{

    public static $_ERROR_EXISTING = '_EXISTING';
    public static $_ERROR_NOT_ALLOWED = '_NOT_ALLOWED';
    public static $_PATH = '';
    public static $instance = null;
    public function __construct()
    { }
    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new FileHelper();
        }
        return self::$instance;
    }

    public function moveFile(ParameterBagInterface $param, $key, $name = ''): string
    {
        if (!isset($_FILES[$key]) || count($_FILES[$key]) <= 0 || $_FILES[$key]['error'] != 0) {
            return '';
        }
        $fileName = $_FILES[$key]['name'];
        if (file_exists($this->getUploadPath($param) . '/' . $fileName)) {
            return self::$_ERROR_EXISTING;
        }
        if (!$this->isValidFile($fileName)) {
            return self::$_ERROR_NOT_ALLOWED;
        }
        if (move_uploaded_file($_FILES[$key]['tmp_name'], $this->getUploadPath($param) . '/' . $fileName)) {
            return $this->getMediaPath() . self::$_PATH . '/' . $fileName;
        }
        return self::$_ERROR_EXISTING;
    }

    private function isValidFile($filename)
    {
        $arr = explode('.', $filename);
        $ext = end($arr);
        if (strpos(getenv('FILE_ALLOWEDS'), '|' . $ext . '|') === false) {
            return false;
        }
        return true;
    }

    public function getRealPathMedia(ParameterBagInterface $param): string
    {
        return $param->get('kernel.project_dir') . $this->getRootPath() . $this->getMediaPath();
    }

    public function getRootPath(): string
    {
        return getenv('ROOT_PATH');
    }

    public function getMediaPath(): string
    {
        return getenv('MEDIA_PATH');
    }

    public function getUploadPath(ParameterBagInterface $param): string
    {
        return $this->getRealPathMedia($param) . self::$_PATH;
    }
}
