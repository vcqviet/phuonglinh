<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;
use HK\CoreBundle\Entity\CmsIpLock;

class CmsIpLockRepository extends MasterRepository
{

    public function lockIp($ip): bool
    {
        $entity = new CmsIpLock();
        $entity->setIpLocked($ip);
        return $this->saveEntity($entity);
    }

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        return parent::customQuery($data, $query);
    }
}