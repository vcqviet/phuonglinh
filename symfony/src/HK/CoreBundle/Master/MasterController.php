<?php

namespace HK\CoreBundle\Master;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Helper\PublishHelper;
use HK\CoreBundle\Entity\CmsRole;
use HK\CoreBundle\Entity\CmsRolePermission;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Helper\DisplayOrderHelper;

class MasterController extends AbstractController
{

    public static $_PHOTO_CLASS = 'rb-photo-list';

    private $translator;

    protected $logger;

    protected $dataFilter = [
        'keyword' => [
            'search_fields' => [],
            'search_by' => ''
        ],
        'from_to' => [
            'from' => '0',
            'to' => '100'
        ]
    ];

    protected $controller = '';

    protected $action = '';

    protected $controllerText = '';

    protected $entityClass = '';

    protected $entityObj = null;

    protected $entityTypeClass = '';

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $repository = null;

    protected $gridColumns = [];

    protected $gridActions = [];

    protected $menuControls = [];

    protected $gridData = [];

    protected $form = null;

    protected $dataRender = [];

    protected $filterForm = [];

    protected $icon = 'activity';

    protected $isDisplayPublishedColumn = false;

    protected $isDisplayCreatedAt = false;

    protected $isDisplayUpdatedBy = false;

    protected $isDisplayUpdatedAt = false;

    protected $isDisplayOrder = false;

    protected $isCheckGranted = true;

    protected $hasContent = false;

    protected $hasPhotoModal = false;

    protected $dataContent = [
        'ref_id' => '-1',
        'attributes' => [
            'Title',
            'Description',
            'Content'
        ],
        'data' => []
    ];

    public function __construct(RequestStack $requestStack, TranslatorInterface $translator, LoggerInterface $logger, ContainerInterface $container)
    {
        // $requestStack->getCurrentRequest()->setLocale(Configuration::instance()->getCurrentLang());
        $controllers = explode('::', $requestStack->getCurrentRequest()->attributes->get('_controller'));
        if (count($controllers) == 2) {
            $this->action = $controllers[1];
            $controllers = explode('\\', $controllers[0]);
            $this->controller = end($controllers);
            $this->controllerText = strtolower(explode('Controller', $this->controller)[0]);
        }
        $this->dataRender['controller_text'] = $this->controllerText;
        $this->dataRender['controller_icon'] = $this->icon;
        $this->dataRender['has_photo_modal'] = '0';

        if (!empty($this->entityClass)) {
            $this->repository = $container->get('doctrine')
                ->getManager()
                ->getRepository($this->entityClass);
        }
        $this->translator = $translator;
        $this->logger = $logger;
    }

    protected function isPermissionView(): bool
    {
        if ($this->isPermissionEdit()) {
            return true;
        }
        return $this->isPermission(CmsRolePermission::$LEVEL_VIEW);
    }

    protected function isPermissionEdit(): bool
    {
        if ($this->isPermissionDelete()) {
            return true;
        }
        return $this->isPermission(CmsRolePermission::$LEVEL_EDIT);
    }

    protected function isPermissionDelete(): bool
    {
        return $this->isPermission(CmsRolePermission::$LEVEL_DELETE);
    }

    private function isPermission($level): bool
    {
        if (!$this->isCheckGranted) {
            return true;
        }
        $roles = $this->getUser()->getRoles();
        foreach ($roles as $role) {
            if ($role === 'ROLE_' . CmsRole::$_ROLE_ADMIN) {
                return true;
            }
        }
        if (empty($this->controllerText)) {
            return false;
        }
        $roles = $this->getUser()->getCmsRoles();
        foreach ($roles as $role) {
            $permissions = $role->getCmsRolePermissions();
            foreach ($permissions as $per) {
                if ($per->getModuleName() == $this->controllerText && $per->isGranted($level)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function filterForm(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        // $this->filterForm[] = [
        //     'name' => 'fpublished',
        //     'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
        //     'value' => '-1',
        //     'placeholder' => $this->trans('phd.filter.is-published'),
        //     'options' => [
        //         [
        //             'value' => -1,
        //             'text' => $this->trans('phd.filter.is-published'),
        //             'attr' => ''
        //         ],
        //         [
        //             'value' => 1,
        //             'text' => $this->trans('opt.is-published-1'),
        //             'attr' => ''
        //         ],
        //         [
        //             'value' => 0,
        //             'text' => $this->trans('opt.is-published-0'),
        //             'attr' => ''
        //         ]
        //     ],
        //     'attr' => '',
        //     'class' => ''
        // ];
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

    public function trans($idString, $parameters = []): string
    {
        return $this->translator->trans($idString, $parameters, null, null);
    }

    protected function save(): Response
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $data = [
            'message' => $this->trans('lbl.form.save-success'),
            'formType' => FormHelper::$_FORM_TYPE_ADD
        ];
        $isSetOrder = false;
        if ($this->entityObj != null && $this->entityObj->getId() > 0) {
            $data['message'] = $this->trans('lbl.form.edit-success');
            $data['formType'] = FormHelper::$_FORM_TYPE_EDIT;
            $data['isComeback'] = true;
        } else {
            $isSetOrder = true;
        }
        $entity = $this->form->getData();
        if (!$this->repository->saveEntity($entity, $this->dataContent)) {
            return $this->errorJson('lbl.form.save-edit-error');
        }
        if ($isSetOrder) {
            $this->repository->setDefaultOrder($entity);
        }
        return $this->okJson($data);
    }

    public function validateFormAfter(&$errorMessage, Request $req): bool
    {
        return true;
    }

    public function itemLang(Request $req)
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $id = $req->get('id', '-1');
        $this->entityObj = $this->repository->bkGetById($id);
        if ($this->entityObj == null) {
            return $this->okJson([]);
        }
        $returnData = [];
        $langs = Configuration::instance()->getAllLanguages();

        foreach ($langs as $lang) {
            $returnData[$lang] = [];
            $langItem = $this->entityObj->getLangContent($lang);
            if ($langItem == null) {
                foreach ($this->dataContent['attributes'] as $attr) {
                    $returnData[$lang][strtolower($attr)] = '';
                }
                continue;
            }
            foreach ($this->dataContent['attributes'] as $attr) {
                $returnData[$lang][strtolower($attr)] = $langItem->{'get' . $attr}();
            }
        }

        return $this->okJson($returnData);
    }

    public function validateFormBefore(Request &$req)
    {
        $dataForm = $req->get($this->form->getName(), []);
        if ($this->hasContent && count($dataForm)) {
            $langs = Configuration::instance()->getAllLanguages();
            foreach ($langs as $lang) {
                $subfix = Configuration::instance()->getCurrentLang() == $lang ? '' : ('_' . $lang);
                $this->dataContent['data'][$lang] = [];
                foreach ($this->dataContent['attributes'] as $attr) {
                    $newAttr = strtolower(substr($attr, 0, 1)) . substr($attr, 1);
                    $this->dataContent['data'][$lang][$attr] = isset($dataForm[$newAttr . $subfix]) ? $dataForm[$newAttr . $subfix] : '';
                    if ($subfix != '') {
                        unset($dataForm[$newAttr . $subfix]);
                    }
                }
            }
        }
        $req->request->set($this->form->getName(), $dataForm);
        return true;
    }

    public function addEdit(Request $req): Response
    {
        if (!$this->isPermissionEdit()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $this->dataRender['is_lang_content'] = '0';
        if ($this->hasContent) {
            $this->dataRender['url_lang_item'] = $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.lang_item');
            $this->dataRender['is_lang_content'] = '1';
        }
        $id = intval($req->get('id', '-1'));
        if ($this->entityObj == null) {
            $this->entityObj = new $this->entityClass();
            if ($id > 0) {
                $this->entityObj = $this->repository->bkGetById($id);
            }
        }
        $this->form = $this->createForm($this->entityTypeClass, $this->entityObj);
        $this->validateFormBefore($req);
        $this->form->handleRequest($req);
        if ($this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $errorMessage = '';
                if ($this->validateFormAfter($errorMessage, $req)) {
                    if ($id <= 0) {
                        $this->entityObj->setCreatedBy($this->getUser()
                            ->getEmailAddress());
                    }
                    $this->entityObj->setUpdatedBy($this->getUser()
                        ->getEmailAddress());
                    return $this->save();
                }
                return $this->errorJson($errorMessage);
            }
            return $this->errorJson('lbl.form.save-edit-error');
        }
        $renderTemplate = $this->isAddEditCustom ? ('admin/' . $this->controllerText . '/add.html.twig') : 'admin/common/pages/add.html.twig';
        $this->dataRender['has_photo_modal'] = $this->hasPhotoModal ? '1' : '0';
        return $this->render($renderTemplate, [
            'form' => $this->form->createView(),
            'breadcrumbs' => [
                [
                    'class' => '',
                    'name' => $this->controllerText,
                    'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText),
                    'active' => ''
                ],
                [
                    'class' => 'active',
                    'name' => $id > 0 ? $this->trans('lbl.title.edit') : $this->trans('lbl.title.add'),
                    'url' => 'javascript:void(0)',
                    'active' => 'active'
                ]
            ],
            'id' => $id,
            'data' => $this->dataRender
        ]);
    }

    public function addActionDisplayOrder(): void
    {
        if ($this->isDisplayOrder && $this->isPermissionEdit()) {
            $this->gridActions['21'] = [
                'name' => 'displayOrderUp',
                'text' => $this->trans('lbl.action-display-order-up'),
                'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.order'),
                'icon' => 'far fa-arrow-alt-circle-up',
                'class' => 'rb-reinit-action',
                'method' => FormHelper::$_METHOD_POST,
                'attr' => 'rb-data-params={"type":"' . DisplayOrderHelper::$_TYPE_UP . '"} rb-callback-after="badmin_reload"'
            ];
            $this->gridActions['22'] = [
                'name' => 'displayOrderDown',
                'text' => $this->trans('lbl.action-display-order-down'),
                'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.order'),
                'icon' => 'far fa-arrow-alt-circle-down',
                'class' => 'rb-reinit-action',
                'method' => FormHelper::$_METHOD_POST,
                'attr' => 'rb-data-params={"type":"' . DisplayOrderHelper::$_TYPE_DOWN . '"} rb-callback-after="badmin_reload"'
            ];
        }
    }

    public function filterDefault(Request $req): void
    {
        $this->dataFilter['keyword']['search_by'] = '%' . str_replace('*', '%', $req->get('fkeyword', '')) . '%';
        // $this->dataFilter['is_deleted'] = 1;
        $this->dataFilter['pagination'] = [
            'limit' => intval($req->get('limit', -1)),
            'page' => intval($req->get('page', -1))
        ];
        $this->dataFilter['is_published_admin'] = $req->get('fpublished', -1);
        foreach ($this->gridColumns as $arr) {
            if (isset($arr['is_filter']) && $arr['is_filter']) {
                if (isset($arr['is_content']) && $arr['is_content']) {
                    $this->dataFilter['keyword']['search_fields'][] = [
                        'key' => $arr['name'],
                        'content' => true
                    ];
                    continue;
                }
                $this->dataFilter['keyword']['search_fields'][] = $arr['name'];
            }
        }
        if (!$this->isPermissionEdit()) {
            return;
        }
        $this->addActionDisplayOrder();

        $this->gridActions['51'] = [
            'name' => 'edit',
            'text' => $this->trans('lbl.action-edit'),
            'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.edit'),
            'icon' => 'fa fa-edit',
            'class' => 'rb-reinit-url',
            'method' => FormHelper::$_METHOD_GET,
            'attr' => ''
        ];
        if (!$this->isPermissionDelete()) {
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

    public function filter(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }

        if ($this->isDisplayCreatedAt) {
            $this->gridColumns[] = [
                'name' => 'createdAt',
                'text' => $this->trans('lbl.created-at'),
                'type' => 'html',
                'width' => '135px'
            ];
        }
        if ($this->isDisplayUpdatedAt) {
            $this->gridColumns[] = [
                'name' => 'updatedAt',
                'text' => $this->trans('lbl.updated-at'),
                'type' => 'html',
                'width' => '135px'
            ];
        }
        if ($this->isDisplayUpdatedBy) {
            $this->gridColumns[] = [
                'name' => 'updatedBy',
                'text' => $this->trans('lbl.updated-by'),
                'type' => 'html',
                'width' => '135px'
            ];
        }
        if ($this->isDisplayPublishedColumn) {
            $this->gridColumns[] = [
                'name' => 'isPublished',
                'text' => $this->trans('lbl.is-published'),
                'type' => 'html',
                'width' => '110px'
            ];
        }
        if (count($this->gridActions)) {
            $this->gridColumns[] = [
                'name' => 'action',
                'text' => $this->trans('lbl.action'),
                'type' => 'html',
                'width' => '150px'
            ];
        }
        ksort($this->gridActions);
        $actions = [];
        foreach ($this->gridActions as $act) {
            $actions[] = $act;
        }
        $data = [
            'columns' => $this->gridColumns,
            'actions' => $actions,
            'data' => $this->gridData,
            'dataRender' => $this->dataRender
        ];
        return $this->okJson($data);
    }

    public function delete(Request $req): Response
    {
        if (!$this->isPermissionDelete()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $ids = [];
        $ids[] = $req->get('id', -1);
        $params = json_decode($req->get('params', '{}'), true);
        if (isset($params['ids'])) {
            $ids = array_merge($ids, $params['ids']);
        }
        foreach ($ids as $id) {
            $this->repository->delete($id);
        }
        $data = [
            'message' => $this->trans('grid.delete-success'),
            'isReload' => true
        ];
        return $this->okJson($data);
    }

    public function order(Request $req): Response
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $data = $req->request->all();
        $data['params'] = json_decode($data['params'], true);
        if ($this->repository->setOrder($data['id'], $data['params']['type'])) {
            $data = [
                'message' => $this->trans('grid.set-order-success'),
                'isReload' => true
            ];
            return $this->okJson($data);
        }
        return $this->errorJson($this->trans('grid.set-order-error'));
    }

    public function publish(Request $req): Response
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $ids = [];
        $ids[] = $req->get('id', -1);
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
                $this->repository->publish($id);
            }
            return $this->okJson($data);
        }
        if (isset($params['type']) && $params['type'] == PublishHelper::$_TYPE_UN_PUBLISHED) {
            foreach ($ids as $id) {
                $this->repository->unPublished($id);
            }
            return $this->okJson($data);
        }
        foreach ($ids as $id) {
            $this->repository->reversePublish($id);
        }
        return $this->okJson($data);
    }

    public function addDefaultMenuControl(): void
    {
        if (!$this->isPermissionEdit()) {
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

        // $this->menuControls[] = [
        //     'name' => 'publish',
        //     'text' => $this->trans('lbl.menu.publish'),
        //     'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.publish'),
        //     'icon' => 'fas fa-check',
        //     'class' => 'rb-reinit-action btn btn-primary mb-2 ml-15 ',
        //     'method' => FormHelper::$_METHOD_POST,
        //     'attr' => 'rb-callback-after="badmin_reload" rb-data-params={"type":"' . PublishHelper::$_TYPE_PUBLISH . '"} rb-callback-before="badmin_menuControlBefore"'
        // ];
        // $this->menuControls[] = [
        //     'name' => 'unpublished',
        //     'text' => $this->trans('lbl.menu.un-published'),
        //     'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.publish'),
        //     'icon' => 'fas fa-minus-circle',
        //     'class' => 'rb-reinit-action btn btn-secondary mb-2 ml-15 ',
        //     'method' => FormHelper::$_METHOD_POST,
        //     'attr' => 'rb-callback-after="badmin_reload" rb-data-params={"type":"' . PublishHelper::$_TYPE_UN_PUBLISHED . '"} rb-callback-before="badmin_menuControlBefore"'
        // ];
        if (!$this->isPermissionDelete()) {
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

    public function menuControl(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->addDefaultMenuControl();
        return $this->okJson($this->menuControls);
    }

    public function index(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $renderTemplate = $this->isIndexCustom ? ('admin/' . $this->controllerText . '/index.html.twig') : 'admin/common/pages/index.html.twig';
        return $this->render($renderTemplate, array(
            'router' => Router::$_PREFIX . $this->controllerText,
            'router_filter' => Router::$_PREFIX . $this->controllerText . '.filter',
            'router_menu_control' => Router::$_PREFIX . $this->controllerText . '.menu_control',
            'router_filter_form' => Router::$_PREFIX . $this->controllerText . '.filter_form',
            'breadcrumbs' => [
                [
                    'class' => '',
                    'name' => $this->controllerText,
                    'url' => 'javascript:void(0)',
                    'active' => 'active'
                ]
            ],
            'data' => $this->dataRender
        ));
    }

    public function outputJson($data): Response
    {
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function okJson($data): Response
    {
        return $this->outputJson([
            'status' => 0,
            'data' => $data
        ]);
    }

    public function errorJson($message): Response
    {
        return $this->outputJson([
            'status' => 1,
            'data' => '',
            'error' => $this->trans($message)
        ]);
    }

    public function titleLangExisting(Request $req): Response
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $val = $req->get('val', '');
        $id = $req->get('id', '-1');
        if (empty($val)) {
            return $this->okJson([
                'isExist' => 0
            ]);
        }
        if ($this->repository->isExistingLang($id, [
            'title' => $val
        ])) {
            return $this->okJson([
                'isExist' => 1
            ]);
        }
        return $this->okJson([
            'isExist' => 0
        ]);
    }
}
