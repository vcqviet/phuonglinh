<?php
namespace HK\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterFrontEndController;
use HK\CoreBundle\Entity\AboutPage;

class AboutController extends MasterFrontEndController
{

    public function index(Request $req): Response
    {
        $this->dataRender['abouts'] = [];
        
        $this->dataRender['content'] = $this->getDoctrine()->getRepository(AboutPage::class)->getByNameKey(AboutPage::$_ABOUT);
        return $this->render('app/about/index.html.twig', [
            'data' => $this->dataRender
        ]);
    }
}
