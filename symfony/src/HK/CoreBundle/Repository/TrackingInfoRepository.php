<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class TrackingInfoRepository extends MasterRepository
{

    protected $hasContent = true;

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        if(!empty($data['gender'])) {
            $query->andWhere('tbl.gender = :gender')->setParameter(':gender', $data['gender']);
        }
        if(!empty($data['deviceId'])) {
            $query->andWhere('tbl.deviceId = :deviceId')->setParameter(':deviceId', $data['deviceId']);
        }
        return parent::customQuery($data, $query);
    }
}