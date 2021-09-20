<?php

namespace HK\ApiBundle\Controller;

use HK\CoreBundle\Entity\News;
use HK\CoreBundle\Master\MasterApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends MasterApiController
{
    public function news(Request $req): Response
    {
        $data = [];
        $filter = ['show_ons' => [News::$_SHOW_ON_ALL, NEws::$_SHOW_ON_APP]];
        $page = intval($req->get('page'));
        $limit = intval($req->get('limit'));
        if ($page > 0 && $limit > 0) {
            $filter['pagination'] = [
                'page' => $page,
                'limit' => $limit
            ];
        }
        $cateId = intval($req->get('cateId'));
        if ($cateId > 0) {
            $filter['cate_id'] = $cateId;
        }
        $news = $this->getDoctrine()->getRepository(News::class)->getData($filter);
        if (isset($news['items'])) {
            $news = $news['items'];
        }
        foreach ($news as $item) {
            $data[] = [
                'id' => $item->getId(),
                'title' => $item->getLangContent()->getTitle(),
                'description' => $item->getLangContent()->getDescription(),
                // 'content' => $item->getLangContent()->getContent(),
                'photoUrl' => getenv('DOMAIN') . $item->getPhotoUrl(),
                'thumbnailUrl' =>  getenv('DOMAIN') . $item->getThumbnailUrl(),
                // 'showOn' => $item->getShowOn(),
                'viewMoreUrl' => $item->getViewmoreUrl(),
                'cate' => [
                    'name' => $item->getCate()->getLangContent()->getTitle(),
                    'id' => $item->getCate()->getId()
                ]
            ];
        }
        return $this->okJson($data);
    }
    public function detail(Request $req, $id): Response
    {
        $item = $this->getDoctrine()->getRepository(News::class)->getById($id);
        if (!$item) {
            return $this->errorJson('Item not found ' . $id);
        }
        $data = [
            'id' => $item->getId(),
            'title' => $item->getLangContent()->getTitle(),
            'description' => $item->getLangContent()->getDescription(),
            'content' => $item->getLangContent()->getContent(),
            'photoUrl' => $item->getPhotoUrl(),
            'thumbnailUrl' => $item->getThumbnailUrl(),
            // 'showOn' => $item->getShowOn(),
            'viewMoreUrl' => $item->getViewmoreUrl(),
            'cate' => [
                'name' => $item->getCate()->getLangContent()->getTitle(),
                'id' => $item->getCate()->getId()
            ]
        ];
        return $this->okJson($data);
    }
}
