<?php
namespace HK\AdminBundle\Controller;

use HK\CoreBundle\Entity\HomeSlider;
use HK\AdminBundle\FormType\HomeSliderType;
use HK\CoreBundle\Master\MasterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HK\CoreBundle\Helper\DateTimeHelper;

class HomeSliderController extends MasterController
{

    protected $entityClass = HomeSlider::class;

    protected $entityTypeClass = HomeSliderType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'home';

    protected $isDisplayOrder = true;

    protected $isDisplayPublishedColumn = true;

    protected $hasContent = true;

    protected $hasPhotoModal = true;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'photoUrl',
                'text' => $this->trans('home-slider.photo'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => false
            ],
            [
                'name' => 'title',
                'text' => $this->trans('home-slider.title'),
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => false
            ]
        ];

        $this->filterDefault($req);
        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            // $item = new HomeSlider();
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                'createdAt' => DateTimeHelper::instance()->getDMY($item->getCreatedAt()),
                'updatedAt' => DateTimeHelper::instance()->getDMY($item->getUpdatedAt()),
                'updatedBy' => $item->getUpdatedBy(),

                'title' => $item->getTitle(),
                'photoUrl' => '<img src="' . $item->getPhotoUrl() . '" class="' . self::$_PHOTO_CLASS . '"/>'
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }
}
