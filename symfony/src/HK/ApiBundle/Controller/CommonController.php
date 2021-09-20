<?php
namespace HK\ApiBundle\Controller;

use HK\CoreBundle\Master\MasterApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommonController extends MasterApiController
{
    public function filterForm(Request $req): Response
    {
        $data = [];
        return $this->okJson($data);
    }
}
