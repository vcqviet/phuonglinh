<?php
namespace HK\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterFrontEndController;

class ErrorExceptionController extends MasterFrontEndController
{

    public function showError(Request $req): Response
    {
        return $this->redirect('/');
    }
} 
