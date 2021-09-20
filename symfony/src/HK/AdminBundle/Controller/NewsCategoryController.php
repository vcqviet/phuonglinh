<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\NewsCategory;
use HK\AdminBundle\FormType\NewsCategoryType;
use HK\CoreBundle\Helper\DateTimeHelper;

class NewsCategoryController extends MasterController
{

    protected $entityClass = NewsCategory::class;

    protected $entityTypeClass = NewsCategoryType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'grid';

    protected $isDisplayOrder = true;

    protected $isDisplayPublishedColumn = true;

    protected $hasContent = true;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'title',
                'text' => $this->trans('news-category.title'),
                'width' => 'auto',
                'is_content' => true,
                'is_filter' => true
            ]
        ];

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
                

                'title' => $item->getTitle(),
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }
}
