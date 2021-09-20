<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\CmsUser;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Helper\FormHelper;
use HK\AdminBundle\FormType\CmsUserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use HK\CoreBundle\Entity\CmsRole;
use HK\CoreBundle\Helper\PublishHelper;
use HK\AdminBundle\FormType\CmsUserChangePasswordType;
use HK\CoreBundle\Helper\DateTimeHelper;

class AdminUserController extends MasterController
{

    protected $entityClass = CmsUser::class;

    protected $entityTypeClass = CmsUserType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = true;

    protected $icon = 'users';

    protected $isDisplayCreatedAt = true;

    protected $isDisplayUpdatedAt = true;

    public function filterForm(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $options = [
            [
                'value' => - 1,
                'text' => $this->trans('phd.filter.user-role'),
                'attr' => ''
            ]
        ];
        $roles = $this->getDoctrine()
            ->getRepository(CmsRole::class)
            ->bkGetData();
        foreach ($roles as $role) {
            $options[] = [
                'value' => $role->getId(),
                'text' => $role->getRoleName(),
                'attr' => ''
            ];
        }

        $this->filterForm[] = [
            'name' => 'frole',
            'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
            'value' => '-1',
            'placeholder' => $this->trans('phd.filter.user-role'),
            'options' => $options,
            'attr' => '',
            'class' => ''
        ];
        return parent::filterForm($req);
    }

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'emailAddress',
                'text' => $this->trans('lbl.user.email-address'),
                'width' => 'auto',
                'is_filter' => true
            ],
            [
                'name' => 'phoneNumber',
                'text' => $this->trans('lbl.user.phone-number'),
                'width' => '220',
                'is_filter' => true
            ],
            [
                'name' => 'roleName',
                'text' => $this->trans('lbl.user.role-name'),
                'width' => '220',
                'is_filter' => false
            ]
        ];

        $this->filterDefault($req);
        $this->gridActions = [];
        // $this->addActionDisplayOrder();
        if ($this->isPermissionEdit()) {
            $this->gridActions[] = [
                'name' => 'edit',
                'text' => $this->trans('lbl.action-edit'),
                'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.edit'),
                'icon' => 'fas fa-user-edit',
                'class' => 'rb-reinit-url',
                'method' => FormHelper::$_METHOD_GET,
                'attr' => ''
            ];
        }

        if ($this->isPermissionDelete()) {
            $this->gridActions[] = [
                'name' => 'delete',
                'text' => $this->trans('lbl.action-delete'),
                'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText . '.delete'),
                'icon' => 'far fa-trash-alt',
                'class' => 'text-danger rb-reinit-action',
                'method' => FormHelper::$_METHOD_POST,
                'attr' => 'rb-data-is-confirm="1" rb-callback-after="badmin_reload" rb-data-is-confirm-text="' . $this->trans('lbl.action-confirm-delete') . '"'
            ];
        }

        $this->dataFilter['not_ids'] = [
            $this->getUser()->getId()
        ];
        $this->dataFilter['role_id'] = $req->get('frole', '-1');
        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            // $item = new CmsUser();
            $role = '';
            foreach ($item->getCmsRoles() as $roleItem) {
                if (strtolower($roleItem->getRoleName()) == 'root') {
                    continue;
                }
                $role .= '<li>' . $roleItem->getRoleName() . '</li>';
            }
            if (! empty($role)) {
                $role = '<ul>' . $role . '</ul>';
            }
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),

                'emailAddress' => $item->getEmailAddress(),
                'phoneNumber' => $item->getPhoneNumber(),
                'roleName' => $role
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }

    public function emailExisting(Request $req): Response
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
            'emailAddress' => $val
        ])) {
            return $this->okJson([
                'isExist' => 1
            ]);
        }
        return $this->okJson([
            'isExist' => 0
        ]);
    }

    public function phoneNumberExisting(Request $req): Response
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
            'phoneNumber' => $val
        ])) {
            return $this->okJson([
                'isExist' => 1
            ]);
        }
        return $this->okJson([
            'isExist' => 0
        ]);
    }

    public function addEditAdminUser(Request $req, UserPasswordEncoderInterface $encoder = null): Response
    {
        if (! $this->isPermissionEdit()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        $id = intval($req->get('id', '-1'));
        if ($id == $this->getUser()->getId()) {
            return $this->redirectToRoute(Router::$_PREFIX . $this->controllerText);
        }
        if ($this->entityObj == null) {
            $this->entityObj = new $this->entityClass();
            if ($id > 0) {
                $this->entityObj = $this->repository->bkGetById($id);
            }
        }
        $this->dataRender['is_lang_content'] = '0';
        $oldPassword = $this->entityObj->getLoginPassword();
        $this->form = $this->createForm($this->entityTypeClass, $this->entityObj);
        $this->form->handleRequest($req);
        if ($this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $errorMessage = '';
                if ($this->validateFormAfter($errorMessage, $req)) {
                    $isOld = false;
                    if (empty($this->entityObj->getLoginPassword())) {
                        $this->entityObj->setLoginPassword($oldPassword);
                        $isOld = true;
                    }
                    if (! $isOld) {
                        $encoded = $encoder->encodePassword($this->entityObj, $this->entityObj->getLoginPassword());
                        $this->entityObj->setLoginPassword($encoded);
                    }

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
            if (intval($id) == $this->getUser()->getId()) {
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
                if (intval($id) == $this->getUser()->getId()) {
                    continue;
                }
                $this->repository->publish($id);
            }
            return $this->okJson($data);
        }
        if (isset($params['type']) && $params['type'] == PublishHelper::$_TYPE_UN_PUBLISHED) {
            foreach ($ids as $id) {
                if (intval($id) == $this->getUser()->getId()) {
                    continue;
                }
                $this->repository->unPublished($id);
            }
            return $this->okJson($data);
        }
        foreach ($ids as $id) {
            if (intval($id) == $this->getUser()->getId()) {
                continue;
            }
            $this->repository->reversePublish($id);
        }
        return $this->okJson($data);
    }

    public function securityChangePassword(Request $req, UserPasswordEncoderInterface $encoder = null): Response
    {
        $this->entityObj = $this->repository->getById($this->getUser()
            ->getId());
        $this->form = $this->createForm(CmsUserChangePasswordType::class, $this->entityObj);
        if ($req->isMethod('POST')) {
            $data = $req->request->all();
            $data = $data['cms_user_change_password'];
            if ($encoder->isPasswordValid($this->entityObj, $data['oldPassword'])) {
                $this->form->handleRequest($req);
                $this->entityObj->setLoginPassword($encoder->encodePassword($this->entityObj, $this->entityObj->getLoginPassword()));
                if ($this->repository->saveEntity($this->entityObj)) {
                    return $this->okJson([
                        'message' => $this->trans('lbl.user.update-password-success'),
                        'isComeback' => true
                    ]);
                }
            }
            return $this->errorJson($this->trans('lbl.user.password-invalid'));
        }
        $renderTemplate = 'admin/common/pages/add.html.twig';
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
                    'name' => $this->trans('lbl.user.change-password'),
                    'url' => 'javascript:void(0)',
                    'active' => 'active'
                ]
            ],
            'data' => $this->dataRender
        ]);
    }
}
