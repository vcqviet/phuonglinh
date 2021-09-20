<?php
namespace HK\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class PaginationHelper
{

    private static $instance = null;

    public function __construct()
    {}

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new PaginationHelper();
        }
        return self::$instance;
    }

    public function getPageLimit(Request $req)
    {
        $page = intval($req->get('page', 1));
        $limit = intval($req->get('limit', - 1));
        if ($page <= 0) {
            $page = 1;
        }
        if ($limit <= 0) {
            $limit = intval(getenv('PAGINATOR_LIMIT_DEFAULT'));
        }
        return [
            'page' => $page,
            'limit' => $limit
        ];
    }

    public function getDataForRender($url, $data = [])
    {
        $length = floor(intval(getenv('PAGINATOR_LENGTH')) / 2);
        $total = intval($data['total_page']);
        $pages = [];
        $from = (intval($data['page']) - $length) > 0 ? (intval($data['page']) - $length) : 1;
        $to = (intval($data['page']) + $length) < $total ? (intval($data['page']) + $length) : $total;
        for ($i = $from; $i <= $to; $i ++) {
            $pages[] = [
                'page' => $i
            ];
        }

        return [
            'prev_page' => (intval($data['page']) - 1) >= 1 ? (intval($data['page']) - 1) : '',
            'first_page' => $from > 1 ? 1 : '',
            'left_page' => $from <= 2 ? '' : $from - 1 ,
            'right_page' => $to >= $total - 1 ? '' : $to + 1,
            'last_page' => $to < $total ? $total : '',
            'next_page' => (intval($data['page']) + 1) <= $total ? (intval($data['page']) + 1) : '',
            'url' => $url,
            'limit' => $data['limit'],
            'pages' => $pages,
            'current_page' => $data['page']
        ];
    }
}
