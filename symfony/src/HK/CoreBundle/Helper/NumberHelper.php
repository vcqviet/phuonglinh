<?php
namespace HK\CoreBundle\Helper;

class NumberHelper
{

    public static function getStringNumber($number, $length): string
    {
        $num = '';
        for ($i = 0; $i < $length - strlen(strval($number)); $i ++) {
            $num .= '0';
        }
        return $num . $number;
    }

    public static function format($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        if (empty($number)) {
            $number = 0;
        }
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    public static function formatPrice($number, $char = '')
    {
        if (empty($char)) {
            $char = '₫';
        }
        return self::format($number) . $char;
    }
}
