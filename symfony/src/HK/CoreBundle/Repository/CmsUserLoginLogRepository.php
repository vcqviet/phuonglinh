<?php

namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;
use HK\CoreBundle\Entity\CmsUserLoginLog;
use HK\CoreBundle\Entity\CmsIpLock;

class CmsUserLoginLogRepository extends MasterRepository
{

    public function log($userName, $ip, $isSuccess): bool
    {
        $entity = new CmsUserLoginLog();

        $entity->setUserName($userName);
        $entity->setLoginIp($ip);
        $entity->setIsSuccess($isSuccess);
        $entity->setLoginAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));

        return $this->saveEntity($entity);
    }
    public function lockIp($id): bool
    {

        $entity = $this->findOneBy(['id' => $id]);
        if ($entity == null) {
            return false;
        }
        return $this->getEntityManager()->getRepository(CmsIpLock::class)->lockIp($entity->getLoginIp());
    }

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        if (isset($data['login_status']) && intval($data['login_status']) >= 0) {
            $query = $query->andWhere('tbl.isSuccess = :login_status')->setParameter(':login_status', $data['login_status']);
        }
        return parent::customQuery($data, $query);
    }
}
