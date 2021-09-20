<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\CmsIpLock;
use HK\AdminBundle\FormType\CmsIpLockType;
use HK\CoreBundle\Helper\DateTimeHelper;

class AdminIpLockController extends MasterController
{

    protected $entityClass = CmsIpLock::class;

    protected $entityTypeClass = CmsIpLockType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'users';

    protected $isDisplayOrder = false;

    protected $isDisplayPublishedColumn = true;

    protected $isDisplayCreatedAt = false;

    protected $isDisplayUpdatedAt = false;
    
    protected $isDisplayUpdatedBy = false;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'ipLocked',
                'text' => $this->trans('lbl.user.ip-lock'),
                'width' => '100px',
                'is_filter' => true
            ]
        ];
        $this->filterDefault($req);

        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            // $item = new CmsIpLock();
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),
                
                'ipLocked' => $item->getIpLocked()
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }

    public function filterDefault(Request $req): void
    {
        $this->dataFilter['keyword']['search_by'] = '%' . str_replace('*', '%', $req->get('fkeyword', '')) . '%';
        $this->dataFilter['is_deleted'] = 1;
        $this->dataFilter['pagination'] = [
            'limit' => intval($req->get('limit', - 1)),
            'page' => intval($req->get('page', - 1))
        ];
        $this->dataFilter['is_published_admin'] = $req->get('fpublished', - 1);
        foreach ($this->gridColumns as $arr) {
            if (isset($arr['is_filter']) && $arr['is_filter']) {
                $this->dataFilter['keyword']['search_fields'][] = $arr['name'];
            }
        }
        if (! $this->isPermissionEdit()) {
            return;
        }
        $this->menuControls[] = [
            'name' => 'addnew',
            'text' => $this->trans('lbl.menu.add-new'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.add'),
            'icon' => 'fas fa-plus',
            'class' => 'rb-reinit-url btn btn-success mb-2 ml-15 ',
            'method' => FormHelper::$_METHOD_GET,
            'attr' => ''
        ];
        if (! $this->isPermissionDelete()) {
            return;
        }
        $this->gridActions['99'] = [
            'name' => 'delete',
            'text' => $this->trans('lbl.action-delete'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.delete'),
            'icon' => 'far fa-trash-alt',
            'class' => 'text-danger rb-reinit-action',
            'method' => FormHelper::$_METHOD_POST,
            'attr' => 'rb-data-is-confirm="1" rb-callback-after="badmin_reload" rb-data-is-confirm-text="' . $this->trans('lbl.action-confirm-delete') . '"'
        ];
    }

    public function filterForm(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }

        $this->filterForm[] = [
            'name' => 'fkeyword',
            'type' => FormHelper::$_ELEMENT_TYPE_TEXT,
            'value' => '',
            'placeholder' => $this->trans('phd.filter.keyword'),
            'attr' => '',
            'class' => ''
        ];
        return $this->okJson($this->filterForm);
    }

    public function addDefaultMenuControl(): void
    {
        if (! $this->isPermissionEdit()) {
            return;
        }
        $this->menuControls[] = [
            'name' => 'addnew',
            'text' => $this->trans('lbl.menu.add-new'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.add'),
            'icon' => 'fas fa-plus',
            'class' => 'rb-reinit-url btn btn-success mb-2 ml-15 ',
            'method' => FormHelper::$_METHOD_GET,
            'attr' => ''
        ];
        if (! $this->isPermissionDelete()) {
            return;
        }
        $this->menuControls[] = [
            'name' => 'delete',
            'text' => $this->trans('lbl.menu.delete'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.delete'),
            'icon' => 'far fa-trash-alt',
            'class' => 'rb-reinit-action btn btn-danger mb-2 ml-15 ',
            'method' => FormHelper::$_METHOD_POST,
            'attr' => 'rb-data-is-confirm="1" rb-callback-after="badmin_reload" rb-data-is-confirm-text="' . $this->trans('lbl.menu.confirm-delete') . '" rb-callback-before="badmin_menuControlBefore"'
        ];
    }
    public function delete(Request $req): Response
    {
        if (! $this->isPermissionDelete()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $ids = [];
        $ids[] = $req->get('id', - 1);
        $params = json_decode($req->get('params', '{}'), true);
        if (isset($params['ids'])) {
            $ids = array_merge($ids, $params['ids']);
        }
        foreach ($ids as $id) {
            $this->repository->remove($id);
        }
        $data = [
            'message' => $this->trans('grid.delete-success'),
            'isReload' => true
        ];
        return $this->okJson($data);
    }
}
