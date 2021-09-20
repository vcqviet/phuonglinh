<?php

namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\TrackingInfo;
use HK\AdminBundle\FormType\TrackingInfoType;
use HK\CoreBundle\Helper\DateTimeHelper;
use HK\CoreBundle\Helper\FormHelper;

class TrackingInfoController extends MasterController
{

    protected $entityClass = TrackingInfo::class;

    protected $entityTypeClass = TrackingInfoType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'home';

    protected $isDisplayOrder = true;

    protected $isDisplayPublishedColumn = true;

    protected $hasContent = true;

    protected $hasPhotoModal = true;
    public function filterForm(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->filterForm[] = [
            'name' => 'fgender',
            'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
            'value' => TrackingInfo::$_GENDER_MALE,
            'placeholder' => $this->trans('phd.gender'),
            'options' => [
                [
                    'value' => '',
                    'text' => $this->trans('phd.gender'),
                    'attr' => ''
                ],
                [
                    'value' => TrackingInfo::$_GENDER_MALE,
                    'text' => $this->trans('opt.gender-male'),
                    'attr' => ''
                ],
                [
                    'value' => TrackingInfo::$_GENDER_FEMALE,
                    'text' => $this->trans('opt.gender-female'),
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
    public function filter(Request $req): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            // [
            //     'name' => 'deviceId',
            //     'text' => $this->trans('tracking-info.device-id'),
            //     'width' => 'auto',
            //     'is_content' => false,
            //     'is_filter' => true
            // ],
            [
                'name' => 'gender',
                'text' => $this->trans('tracking-info.gender'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ],
            [
                'name' => 'platform',
                'text' => $this->trans('tracking-info.platform'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ]
        ];

        $this->filterDefault($req);
        $this->dataFilter['gender'] = $req->get('fgender', '');
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


                // 'deviceId' => $item->getDeviceId(),
                'gender' => $item->getGender() == TrackingInfo::$_GENDER_MALE ? $this->trans('tracking-info.gender-male') : $this->trans('tracking-info.gender-female'),
                'platform' => $item->getPlatform(),
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }
}
