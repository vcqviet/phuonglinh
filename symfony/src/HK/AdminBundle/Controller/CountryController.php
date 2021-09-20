<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\Country;
use HK\AdminBundle\FormType\CountryType;

class CountryController extends MasterController
{

    protected $entityClass = Country::class;

    protected $entityTypeClass = CountryType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'database';

    protected $isDisplayPublishedColumn = true;

    protected $isDisplayCreatedAt = false;

    protected $isDisplayUpdatedBy = false;

    protected $isDisplayUpdatedAt = false;

    protected $isDisplayOrder = true;

    protected $hasContent = false;

    public function filter(Request $req): Response
    {
        if (! $this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->gridColumns = [
            [
                'name' => 'name',
                'text' => 'Tên quốc gia',
                'width' => 'auto',
                'is_content' => false,
                'is_filter' => true
            ]
        ];
        
        $this->filterDefault($req);
        $this->gridData = $this->repository->bkGetData($this->dataFilter);
        $returnArr = [];
        foreach ($this->gridData['items'] as $item) {
            
            $returnArr[] = [
                'id' => $item->getId(),
                'isPublished' => $item->getIsPublished(),
                
                'name' => $item->getName()
            ];
        }
        $this->gridData['items'] = $returnArr;
        return parent::filter($req);
    }

    public function nameExisting(Request $req): Response
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
            'name' => $val
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
