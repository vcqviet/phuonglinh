<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class SettingWebsiteCategoryRepository extends MasterRepository
{

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        if (isset($data['type']) && ! empty($data['type'])) {
            $query = $query->andWhere('tbl.type = :type')->setParameter(':type', $data['type']);
        }
        return parent::customQuery($data, $query);
    }
}