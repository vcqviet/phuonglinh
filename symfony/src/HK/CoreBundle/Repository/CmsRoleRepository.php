<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class CmsRoleRepository extends MasterRepository
{

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        $query = $query->andWhere('tbl.id != 1');
        $query = $query->andWhere('tbl.id != 2');
        return parent::customQuery($data, $query);
    }
}