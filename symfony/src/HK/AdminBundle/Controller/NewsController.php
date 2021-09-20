<?php

namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\News;
use HK\AdminBundle\FormType\NewsType;
use HK\CoreBundle\Helper\DateTimeHelper;
use HK\CoreBundle\Helper\FormHelper;

class NewsController extends MasterController
{

    protected $entityClass = News::class;
    protected $entityTypeClass = NewsType::class;
    protected $isIndexCustom = false;
    protected $isAddEditCustom = false;
    protected $icon = 'grid';
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
            'name' => 'fshowon',
            'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
            'value' => News::$_SHOW_ON_ALL,
            'placeholder' => $this->trans('phd.filter.show-on'),
            'options' => [
                [
                    'value' => '',
                    'text' => $this->trans('phd.filter.show-on'),
                    'attr' => ''
                ],
                [
                    'value' => News::$_SHOW_ON_ALL,
                    'text' => $this->trans('opt.show-on-all'),
                    'attr' => ''
                ],
                [
                    'value' => News::$_SHOW_ON_APP,
                    'text' => $this->trans('opt.show-on-app'),
                    'attr' => ''
                ],
                [
                    'value' => News::$_SHOW_ON_WEB,
                    'text' => $this->trans('opt.show-on-web'),
                    'attr' => ''
                ]
            ],
            'attr' => '',
            'class' => ''
        ];
        $this->filterForm[] = [
            'name' => 'fviewmore',
            'type' => FormHelper::$_ELEMENT_TYPE_SELECT,
            'value' => -1,
            'placeholder' => $this->trans('phd.filter.view-more'),
            'options' => [
                [
                    'value' => -1,
                    'text' => $this->trans('phd.filter.view-more'),
                    'attr' => ''
                ],
                [
                    'value' => 1,
                    'text' => $this->trans('opt.view-more-1'),
                    'attr' => ''
                ],
                [
                    'value' => 0,
                    'text' => $this->trans('opt.view-more-0'),
                    'attr' => ''
                ],
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
            [
                'name' => 'photoUrl',
                'text' => $this->trans('news.photo'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ],
            [
                'name' => 'title',
                'text' => $this->trans('news.title'),
                'width' => 'auto',
                'is_content' => true,
                'is_filter' => true
            ],
            [
                'name' => 'showOn',
                'text' => $this->trans('news.show-on'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => false
            ],
            [
                'name' => 'viewmoreUrl',
                'text' => 'View More Url',
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ]
        ];

        $this->filterDefault($req);
        $this->dataFilter['show_on'] = $req->get('fshowon', News::$_SHOW_ON_ALL);
        $this->dataFilter['is_viewmore'] = $req->get('fviewmore', -1);
        if (intval($this->dataFilter['is_viewmore']) == -1) {
            unset($this->dataFilter['is_viewmore']);
        }
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


                'title' => $item->getTitle(),
                'showOn' => $item->getShowOn() == News::$_SHOW_ON_ALL ? $this->trans('news.show-on-all') : ($item->getShowOn() == News::$_SHOW_ON_APP ? $this->trans('news.show-on-app') : $this->trans('news.show-on-web')),
                'viewmoreUrl' => $item->getViewmoreUrl(),
                'photoUrl' => '<img src="' . $item->getPhotoUrl() . '" class="' . self::$_PHOTO_CLASS . '"/>'
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }
}
