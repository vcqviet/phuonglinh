<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class NewsContentRepository extends MasterRepository
{

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        return parent::customQuery($data, $query);
    }
}