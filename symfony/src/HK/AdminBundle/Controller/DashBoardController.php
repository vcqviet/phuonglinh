<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;

class DashBoardController extends MasterController
{

    protected $isCheckGranted = false;

    public function index(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->redirectToRoute('hk_admin_login');
        }
        return $this->render('admin/dashboard/index.html.twig', [
            'data' => $this->dataRender
        ]);
    }
}
