<?php

namespace HK\ApiBundle\Controller;

use HK\CoreBundle\Entity\HomeSlider;
use HK\CoreBundle\Master\MasterApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SlideController extends MasterApiController
{
    public function slides(Request $req): Response
    {
        $data = [];
        $slides = $this->getDoctrine()->getRepository(HomeSlider::class)->getData();
        foreach ($slides as $item) {
            $data[] = [
                'id' => $item->getId(),
                'photoUrl' => getenv('DOMAIN') . $item->getPhotoUrl()
            ];
        }
        return $this->okJson($data);
    }
}
