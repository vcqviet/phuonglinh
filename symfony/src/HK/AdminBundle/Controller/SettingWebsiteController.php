<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\AdminBundle\FormType\AboutPageType;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Entity\SettingWebsite;
use HK\CoreBundle\Entity\SettingWebsiteCategory;

class SettingWebsiteController extends MasterController
{

    protected $entityClass = SettingWebsite::class;

    protected $entityTypeClass = AboutPageType::class;

    protected $icon = 'settings';

    protected $hasPhotoModal = true;

    public function indexSetting(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->dataRender['has_photo_modal'] = '1';
        $this->dataRender['cates'] = [];
        $data = $this->getDoctrine()
            ->getRepository(SettingWebsiteCategory::class)
            ->getData([
            'type' => SettingWebsiteCategory::$_TYPE_GENERAL,
            'display_orders' => [
                'displayOrder' => 'ASC'
            ]
        ]);
        foreach ($data as $dt) {
            $cate = [
                'id' => $dt->getId(),
                'name' => $dt->getName(),
                'settings' => []
            ];
            $settings = $this->getDoctrine()
                ->getRepository(SettingWebsite::class)
                ->getData([
                'cate_id' => $dt->getId(),
                'display_orders' => [
                    'displayOrder' => 'asc'
                ]
            ]);

            foreach ($settings as $st) {
                $setting = [
                    'id' => $st->getId(),
                    'name' => $st->getName(),
                    'value' => $st->getValue(),
                    'options' => [],
                    'name_key' => $st->getNameKey(),
                    'type' => $st->getType(),
                    'attrs' => implode(' ', $st->getAttribute()),
                    'noted' => $st->getNoted(),
                    'options' => []
                ];
                foreach ($st->getOptions() as $op) {
                    $selected = $op->getIsDefault() ? 'selected' : '';
                    $checked = $op->getIsDefault() ? 'checked' : '';
                    if (! empty($st->getValue())) {
                        $selected = $st->getValue() == $op->getValue() ? 'selected' : '';
                        $checked = $st->getValue() == $op->getValue() ? 'checked' : '';
                    }
                    $setting['options'][] = [
                        'value' => $op->getValue(),
                        'name' => $op->getName(),
                        'selected' => $selected,
                        'checked' => $checked
                    ];
                }
                $cate['settings'][] = $setting;
            }
            $this->dataRender['cates'][] = $cate;
        }

        if ($req->isMethod('GET')) {
            return $this->render('admin/' . $this->controllerText . '/index.html.twig', [
                'breadcrumbs' => [
                    [
                        'class' => '',
                        'name' => $this->controllerText,
                        'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText),
                        'active' => ''
                    ]
                ],
                'data' => $this->dataRender
            ]);
        }
        $data = $req->request->all();
        $settings = $this->repository->getData();
        foreach ($settings as $st) {
            if (isset($data[$st->getNameKey()])) {
                $this->repository->updateValue($st->getNameKey(), $data[$st->getNameKey()]);
            }
        }
        return $this->okJson([
            'message' => $this->trans('setting-website.save-success'),
            'isReload' => '1'
        ]);
    }
}
