<?php
namespace HK\AdminBundle\Controller;

use HK\CoreBundle\Master\MasterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HK\CoreBundle\Entity\SettingMailTemplate;
use HK\AdminBundle\FormType\SettingMailTemplateType;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Helper\DateTimeHelper;

class SettingMailTemplateController extends MasterController
{

    protected $entityClass = SettingMailTemplate::class;

    protected $entityTypeClass = SettingMailTemplateType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = true;

    protected $icon = 'settings';

    protected $isDisplayOrder = false;

    protected $isDisplayPublishedColumn = true;

    protected $isDisplayCreatedAt = false;

    protected $isDisplayUpdatedAt = false;

    protected $hasContent = false;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [

            [
                'name' => 'title',
                'text' => $this->trans('setting-mail-template.title'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => false
            ],
            [
                'name' => 'subject',
                'text' => $this->trans('setting-mail-template.subject'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ]
        ];

        $this->filterDefault($req);
        unset($this->gridActions['99']);
        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            // $item = new HomeBanner();
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),

                'title' => $this->trans($this->controllerText . '.' . $item->getNameKey()),
                'subject' => $item->getSubject()
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }

    public function filterForm(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->filterForm = [];
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
        $this->menuControls = [];
    }
}
