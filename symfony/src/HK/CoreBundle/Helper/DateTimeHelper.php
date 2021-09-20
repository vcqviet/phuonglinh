<?php

namespace HK\CoreBundle\Helper;

class DateTimeHelper
{

    public static $DATE_FORMAT = 'Y/m/d';

    public static $DATE_FORMAT_DMY = 'd/m/Y';

    public static $DATE_TIME_FORMAT = 'Y/m/d H:i:s';

    public static $DATE_TIME_FORMAT_DMY = 'd/m/Y H:i:s';

    public static $TIME_HOUR_0 = '00:00:00';

    public static $TIME_HOUR_24 = '23:59:59';

    public static $DATE_FORMAT_FOR_TYPE = 'dd/MM/yyyy';

    private static $instance = null;

    public function __construct()
    { }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new DateTimeHelper();
        }
        return self::$instance;
    }

    public function getDMY(?\DateTime $dt)
    {
        if ($dt == null) {
            return '';
        }
        return $dt->format(self::$DATE_FORMAT_DMY);
    }
    public function getCurrentYMD()
    {
        $dt = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $dt->format('Y-m-d');
    }
    public function getFirstDateMonthYMD(?\DateTime $dt = null)
    {
        $dt = $dt ?? new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $dt->format('Y-m-') . '01';
    }
    public function fromDMYToYMD(string $date, string $time = '', string $char = '/')
    {
        $arr = explode($char, $date);
        if (count($arr) != 3) {
            return '';
        }
        return $arr[2] . $char . $arr[1] . $char . $arr[0] . ($time == '' ? '' : (' ' . $time));
    }
    public function getDateTimeFromExcel($excelDate)
    {
        if(strpos($excelDate, '/') > 0) {
            return new \DateTime($this->fromDMYToYMD($excelDate), new \DateTimeZone('Asia/Ho_Chi_Minh'));
        }
        $UNIX_DATE = (intval($excelDate) - 25569) * 86400;
        return new \DateTime(gmdate("d-m-Y H:i:s", $UNIX_DATE));
    }
}
