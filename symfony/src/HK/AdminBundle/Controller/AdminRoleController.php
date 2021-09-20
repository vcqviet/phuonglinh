<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\CmsRole;
use HK\AdminBundle\FormType\CmsRoleType;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Helper\PublishHelper;
use HK\CoreBundle\MenuAdmin\MenuAdmin;
use HK\CoreBundle\Entity\CmsRolePermission;
use HK\CoreBundle\Helper\DateTimeHelper;

class AdminRoleController extends MasterController
{

    protected $entityClass = CmsRole::class;

    protected $entityTypeClass = CmsRoleType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'users';

    protected $isDisplayCreatedAt = true;

    protected $isDisplayUpdatedAt = true;
    
    protected $isDisplayPublishedColumn = true;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'roleName',
                'text' => $this->trans('lbl.role.role-name'),
                'width' => 'auto',
                'is_filter' => true
            ]
        ];
        if ($this->isPermissionEdit()) {
            $this->gridActions['71'] = [
                'name' => 'permission',
                'text' => $this->trans('lbl.action-permission'),
                'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.permission'),
                'icon' => 'fas fa-user-lock',
                'class' => 'text-warning rb-reinit-url',
                'method' => FormHelper::$_METHOD_POST,
                'attr' => ''
            ];
        }

        $this->filterDefault($req);
        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            // $item = new CmsRole();
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),

                'roleName' => $item->getRoleName()
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }

    public function roleExisting(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $val = $req->get('val', '');
        $id = $req->get('id', '-1');
        if (empty($val)) {
            return $this->okJson([
                'isExist' => 0
            ]);
        }
        if ($this->repository->isExisting($id, [
            'roleName' => $val
        ])) {
            return $this->okJson([
                'isExist' => 1
            ]);
        }
        return $this->okJson([
            'isExist' => 0
        ]);
    }

    public function addEdit(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $id = $req->get('id', - 1);
        if (intval($id) == 1 || intval($id) == 2) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        return parent::addEdit($req);
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
            if (intval($id) == 1 || intval($id) == 2) {
                continue;
            }
            $this->repository->delete($id);
        }
        $data = [
            'message' => $this->trans('grid.delete-success'),
            'isReload' => true
        ];
        return $this->okJson($data);
    }

    public function publish(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $ids = [];
        $ids[] = $req->get('id', - 1);
        $params = json_decode($req->get('params', '{}'), true);
        if (isset($params['ids'])) {
            $ids = array_merge($ids, $params['ids']);
        }
        $data = [
            'message' => $this->trans('grid.set-publish-success'),
            'isReload' => true
        ];
        if (isset($params['type']) && $params['type'] == PublishHelper::$_TYPE_PUBLISH) {
            foreach ($ids as $id) {
                if (intval($id) == 1 || intval($id) == 2) {
                    continue;
                }
                $this->repository->publish($id);
            }
            return $this->okJson($data);
        }
        if (isset($params['type']) && $params['type'] == PublishHelper::$_TYPE_UN_PUBLISHED) {
            foreach ($ids as $id) {
                if (intval($id) == 1 || intval($id) == 2) {
                    continue;
                }
                $this->repository->unPublished($id);
            }
            return $this->okJson($data);
        }
        foreach ($ids as $id) {
            if (intval($id) == 1 || intval($id) == 2) {
                continue;
            }
            $this->repository->reversePublish($id);
        }
        return $this->okJson($data);
    }

    public function permissionAccess(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $id = intval($req->get('id', - 1));
        $role = $this->repository->bkGetById($id);
        if ($role == null) {
            return $this->errorJson($this->trans('lbl.role.error-not-found'));
        }
        $permissions = $role->getCmsRolePermissions(false);
        $data = [];
        foreach ($permissions as $per) {
            $data[$per->getModuleName()] = [
                'url' => $per->getUrlAction(),
                'access_rights' => [
                    CmsRolePermission::$LEVEL_VIEW => $per->isGranted(CmsRolePermission::$LEVEL_VIEW),
                    CmsRolePermission::$LEVEL_EDIT => $per->isGranted(CmsRolePermission::$LEVEL_EDIT),
                    CmsRolePermission::$LEVEL_DELETE => $per->isGranted(CmsRolePermission::$LEVEL_DELETE)
                ]
            ];
        }
        return $this->okJson($data);
    }

    public function permissionUpdate(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $id = intval($req->get('id', - 1));
        $module = $req->get('module', '');
        $level = $req->get('level', '');

        if ($this->getDoctrine()
            ->getRepository(CmsRolePermission::class)
            ->addOrUpdate($id, $module, $level)) {
            return $this->okJson([
                'message' => $this->trans('lbl.role.permission-update-success')
            ]);
        }
        return $this->errorJson($this->trans('lbl.role.permission-update-error'));
    }

    public function permission(Request $req): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $id = intval($req->get('id', - 1));
        if ($id < 0) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $menu = MenuAdmin::instance()->getMenuAdmin();

        $this->dataRender['level_view'] = CmsRolePermission::$LEVEL_VIEW;
        $this->dataRender['level_edit'] = CmsRolePermission::$LEVEL_EDIT;
        $this->dataRender['level_delete'] = CmsRolePermission::$LEVEL_DELETE;

        $this->dataRender['modules'] = [];
        foreach ($menu as $key => $item) {
            if (isset($item['type']) && $item['type'] == MenuAdmin::$_TYPE_SESSION) {
                $this->dataRender['modules'][] = [
                    'id' => - 1,
                    'name' => $key,
                    'url' => '',
                    'type' => MenuAdmin::$_TYPE_SESSION
                ];
                foreach ($item['menu'] as $keyMenu => $itemMenu) {
                    if (isset($itemMenu['type']) && $itemMenu['type'] == MenuAdmin::$_TYPE_GROUP) {
                        $this->dataRender['modules'][] = [
                            'id' => - 1,
                            'name' => $keyMenu,
                            'url' => '',
                            'icon' => $itemMenu['icon'],
                            'type' => MenuAdmin::$_TYPE_GROUP
                        ];
                        foreach ($itemMenu['menu'] as $itemMenu2) {
                            $this->dataRender['modules'][] = [
                                'id' => - 1,
                                'name' => $itemMenu2['text'],
                                'url' => $itemMenu2['url'],
                                'icon' => $itemMenu2['icon'],
                                'type' => MenuAdmin::$_TYPE_MENU
                            ];
                        }
                        continue;
                    }
                    $this->dataRender['modules'][] = [
                        'id' => - 1,
                        'name' => $itemMenu['text'],
                        'url' => $itemMenu['url'],
                        'icon' => $itemMenu['icon'],
                        'type' => MenuAdmin::$_TYPE_MENU
                    ];
                }
                continue;
            }
            if (isset($item['type']) && $item['type'] == MenuAdmin::$_TYPE_GROUP) {
                $this->dataRender['modules'][] = [
                    'id' => - 1,
                    'name' => $key,
                    'url' => '',
                    'icon' => $item['icon'],
                    'type' => MenuAdmin::$_TYPE_GROUP
                ];
                foreach ($item['menu'] as $itemMenu) {
                    $this->dataRender['modules'][] = [
                        'id' => - 1,
                        'name' => $itemMenu['text'],
                        'url' => $itemMenu['url'],
                        'icon' => $itemMenu['icon'],
                        'type' => MenuAdmin::$_TYPE_MENU
                    ];
                }
                continue;
            }

            $this->dataRender['modules'][] = [
                'id' => - 1,
                'name' => $item['text'],
                'url' => $item['url'],
                'icon' => $item['icon'],
                'type' => MenuAdmin::$_TYPE_MENU
            ];
        }
        $this->dataRender['role_id'] = $id;
        $this->dataRender['router_permission'] = Router::$_PREFIX . $this->controllerText . '.permission-access';
        $this->dataRender['router_permission_update'] = Router::$_PREFIX . $this->controllerText . '.permission-update';
        return $this->render('admin/' . $this->controllerText . '/permission.html.twig', [
            'data' => $this->dataRender,
            'breadcrumbs' => [
                [
                    'class' => '',
                    'name' => $this->controllerText,
                    'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText),
                    'active' => ''
                ],
                [
                    'class' => 'active',
                    'name' => 'Permission',
                    'url' => 'javascript:void(0)',
                    'active' => 'active'
                ]
            ]
        ]);
    }
}
