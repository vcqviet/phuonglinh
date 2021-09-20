<?php

namespace HK\CoreBundle\Helper;

class StringHelper
{

    public static $CHARACTER = 'Bi@z';

    public static function encode($raw): string
    {
        return sha1(md5($raw) . self::$CHARACTER);
    }

    public static function encodeDateTime(?\DateTime $now = null): string
    {
        if ($now == null) {
            $now = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        }
        return self::encode($now->format(DateTimeHelper::$DATE_FORMAT));
    }

    public static function isValidEncode($hash, $raw): bool
    {
        return $hash === self::encode($raw);
    }

    public static function replaceTemplate($data = [], $content): string
    {
        foreach ($data as $key => $val) {
            $content = str_replace('{{' . $key . '}}', $val, $content);
        }
        return $content;
    }

    public static function uksort(&$array)
    {
        uksort($array, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            $c = new \Collator('vi_VN');

            return $c->compare($a, $b) < 0 ? -1 : 1;
        });
        return $array;
    }

    public static function encodeMSSQL($str, $char = '-'): string
    {
        if (!$str) {
            return false;
        }
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => '(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)',
            'E' => '(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)',
            'I' => '(Ì|Í|Ị|Ỉ|Ĩ)',
            'O' => '(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)',
            'U' => '(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)',
            'Y' => '(Ỳ|Ý|Ỵ|Ỷ|Ỹ)',
            'D' => '(Đ)',

            $char => '(#|~|`|!|@|%|&|\*|_|=|{|[|]|}|:|;|<|,|>|\.|\?|\/|\$|\^|\\\\|"|\'|\(|\)|\+|\|)'
        ); // |#
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = strtolower($str);
        while (strpos($str, $char . $char) !== false) {
            $str = str_replace($char . $char, $char, $str);
        }
        return $str;
    }

    public static function encodeTitle($str, $char = '-'): string
    {
        if (!$str) {
            return false;
        }
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => '(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)',
            'E' => '(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)',
            'I' => '(Ì|Í|Ị|Ỉ|Ĩ)',
            'O' => '(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)',
            'U' => '(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)',
            'Y' => '(Ỳ|Ý|Ỵ|Ỷ|Ỹ)',
            'D' => '(Đ)',

            '-' => '(#|~| |`|!|@|%|&|\*|_|=|{|[|]|}|:|;|<|,|>|\.|\?|\/|\$|\^|\\\\|"|\'|\(|\)|\+|\|)'
        ); // |#
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = strtolower($str);
        while (strpos($str, $char . $char) !== false) {
            $str = str_replace($char . $char, $char, $str);
        }
        return $str;
    }
}
