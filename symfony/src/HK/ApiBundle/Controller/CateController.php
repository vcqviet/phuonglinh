<?php
namespace HK\ApiBundle\Controller;

use HK\CoreBundle\Entity\NewsCategory;
use HK\CoreBundle\Master\MasterApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CateController extends MasterApiController
{
    public function cates(Request $req): Response
    {
        $data = [];
        $cates = $this->getDoctrine()->getRepository(NewsCategory::class)->getData();
        foreach($cates as $cate) {
            $data[] = [
                'id' => $cate->getId(),
                'title' => $cate->getLangContent()->getTitle()
            ];
        }
        return $this->okJson($data);
    }
}
