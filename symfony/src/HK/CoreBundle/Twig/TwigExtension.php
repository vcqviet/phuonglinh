<?php
namespace HK\CoreBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use HK\CoreBundle\Helper\StringHelper;

class TwigExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('price', [
                $this,
                'formatPrice'
            ]),
            new TwigFilter('env', [
                $this,
                'getEnv'
            ]),
            new TwigFilter('rbu', [
                $this,
                'titleToUrl'
            ])
        ];
    }

    public function formatPrice($number, $char = '$', $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $char . $price;

        return $price;
    }

    public function getEnv($key)
    {
        return getenv($key);
    }

    public function titleToUrl($title, $seo = '', $char = '-')
    {
        if(!empty($seo)) {
            return $seo;
        }
        return StringHelper::encodeTitle($title, $char);
    }

    public function getFunctions()
    {
        return [ // new TwigFunction('area', [$this, 'calculateArea']),
        ];
    }
}