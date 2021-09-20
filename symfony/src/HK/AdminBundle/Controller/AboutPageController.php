<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Entity\AboutPage;
use HK\AdminBundle\FormType\AboutPageType;
use HK\CoreBundle\Helper\FormHelper;
use HK\AdminBundle\FormType\AboutPagePdfType;

class AboutPageController extends MasterController
{

    protected $entityClass = AboutPage::class;

    protected $entityTypeClass = AboutPageType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'info';

    protected $isDisplayOrder = false;

    protected $isDisplayPublishedColumn = false;

    protected $hasContent = true;

    protected function save(): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $data = [
            'message' => $this->trans('lbl.form.save-success'),
            'formType' => FormHelper::$_FORM_TYPE_ADD
        ];
        if ($this->entityObj != null && $this->entityObj->getId() > 0) {
            $data['message'] = $this->trans('lbl.form.edit-success');
            $data['formType'] = FormHelper::$_FORM_TYPE_EDIT;
            $data['isComeback'] = 'none';
        }
        if (! $this->repository->saveEntity($this->form->getData(), $this->dataContent)) {
            return $this->errorJson('lbl.form.save-edit-error');
        }
        return $this->okJson($data);
    }

    public function addEdit(Request $req): Response
    {
        $nameKey = $req->get('namekey', '');
        
        $this->controllerText = str_replace('_', '', strtolower($nameKey));
        if (! $this->isPermissionEdit()) {
            return $this->redirectToRoute('hk_admin_dashboard');
        }
        
        $this->entityObj = $this->repository->bkGetByNameKey($nameKey);
        if ($this->entityObj == null) {
            return $this->redirectToRoute('hk_admin_dashboard');
        }
        $this->dataRender['page_header'] = $this->trans($this->controllerText);
        $this->dataRender['controller_text'] = '';
        
        $this->dataRender['url_lang_item'] = $this->generateUrl(Router::$_PREFIX . 'aboutpage.lang_item');
        $this->dataRender['is_lang_content'] = '1';
        
        $this->form = $this->createForm($this->entityTypeClass, $this->entityObj);
        $this->validateFormBefore($req);
        $this->form->handleRequest($req);
        if ($this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $errorMessage = '';
                if ($this->validateFormAfter($errorMessage, $req)) {
                    
                    $this->entityObj->setUpdatedBy($this->getUser()
                        ->getEmailAddress());
                    return $this->save();
                }
                return $this->errorJson($errorMessage);
            }
            return $this->errorJson('lbl.form.save-edit-error');
        }
        $renderTemplate = $this->isAddEditCustom ? ('admin/' . $this->controllerText . '/add.html.twig') : 'admin/common/pages/add.html.twig';
        
        return $this->render($renderTemplate, [
            'form' => $this->form->createView(),
            'breadcrumbs' => [
                [
                    'class' => '',
                    'name' => $this->controllerText,
                    'url' => $this->generateUrl(Router::$_PREFIX . 'aboutpage.' . $this->controllerText),
                    'active' => ''
                ]
            ],
            'id' => $this->entityObj->getId(),
            'data' => $this->dataRender
        ]);
    }

    public function addEditPdf(Request $req): Response
    {
        $this->entityTypeClass = AboutPagePdfType::class;
        return $this->addEdit($req);
    }
}
