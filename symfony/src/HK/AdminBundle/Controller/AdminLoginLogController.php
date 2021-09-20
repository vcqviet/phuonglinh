<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\AdminBundle\FormType\CmsRoleType;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Helper\DateTimeHelper;
use HK\CoreBundle\Entity\CmsUserLoginLog;

class AdminLoginLogController extends MasterController
{

    protected $entityClass = CmsUserLoginLog::class;

    protected $entityTypeClass = CmsRoleType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'users';
    
    protected $isDisplayOrder = false;
    
    protected $isDisplayPublishedColumn = false;

    protected $isDisplayCreatedAt = false;

    protected $isDisplayUpdatedAt = false;

    protected $isDisplayUpdatedBy = false;

    public function filter(Request $req): Response
    {
        if(!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'time',
                'text' => $this->trans('lbl.user-login-log.time'),
                'width' => '300px',
                'is_filter' => false
            ],
            [
                'name' => 'status',
                'text' => $this->trans('lbl.user-login-log.status'),
                'width' => '100px',
                'is_filter' => false
            ],
            [
                'name' => 'loginIp',
                'text' => $this->trans('lbl.user-login-log.ip'),
                'width' => '100px',
                'is_filter' => true
            ],
            [
                'name' => 'userName',
                'text' => $this->trans('lbl.user-login-log.email'),
                'width' => 'auto',
                'is_filter' => true
            ]
        ];
        $this->filterDefault($req);
        $this->dataFilter['login_status'] = $req->get('floginstatus', '-1');

        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            //$item = new CmsUserLoginLog();
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),

                'time' => $item->getLoginAt()->format(DateTimeHelper::$DATE_TIME_FORMAT_DMY),
                'status' => $item->getIsSuccess() ? '<i class="text-success fas fa-check"></i>' : '<i class="text-disabled fas fa-minus-circle"></i>',
                'loginIp' => $item->getLoginIp(),
                'userName' => $item->getUserName()
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
        $this->gridActions['71'] = [
            'name' => 'lockIp',
            'text' => $this->trans('lbl.action-lock-ip'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.lock_ip'),
            'icon' => 'fas fa-user-lock',
            'class' => 'text-warning rb-reinit-action',
            'method' => FormHelper::$_METHOD_POST,
            'attr' => 'rb-data-is-confirm="1" rb-callback-after="badmin_reload" rb-data-is-confirm-text="' . $this->trans('lbl.action-confirm-lock-ip') . '"'
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
            'name' => 'floginstatus',
            'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
            'value' => '-1',
            'placeholder' => $this->trans('phd.filter.login-status'),
            'options' => [
                [
                    'value' => - 1,
                    'text' => $this->trans('phd.filter.login-status'),
                    'attr' => ''
                ],
                [
                    'value' => 1,
                    'text' => $this->trans('phd.filter.login-status-1'),
                    'attr' => ''
                ],
                [
                    'value' => 0,
                    'text' => $this->trans('phd.filter.login-status-0'),
                    'attr' => ''
                ]
            ],
            'attr' => '',
            'class' => ''
        ];
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

    public function lockIp(Request $req): Response
    {
        if (! $this->isPermissionDelete()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $id = $req->get('id', - 1);
        $this->repository->lockIp($id);
        $data = [
            'message' => $this->trans('grid.lock-ip-success'),
            'isReload' => false
        ];
        return $this->okJson($data);
    }
}
