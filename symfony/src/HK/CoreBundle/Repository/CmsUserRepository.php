<?php

namespace HK\CoreBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class CmsUserRepository extends MasterRepository implements UserLoaderInterface
{

    public function loadUserByUsername($username)
    {
        $q = $this->createQueryBuilder('tbl')
            ->where('tbl.isDeleted = 0')
            ->andWhere('(tbl.emailAddress = :emailAddress OR tbl.phoneNumber = :emailAddress)')
            ->andWhere('tbl.isPublished = 1')
            ->setParameter(':emailAddress', $username);
        return $q->getQuery()->getOneOrNullResult();
    }

    public function forgotPassword(string $email = '')
    {
        $entity = $this->findOneBy([
            'emailAddress' => $email,
            'isDeleted' => 0,
            'isPublished' => 1
        ]);
        if ($entity == null) {
            return null;
        }
        $entity->setRecoverTime(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));

        $this->saveEntity($entity);
        return $entity;
    }

    public function resetPassword($id, $newPassword)
    {
        $entity = $this->getById($id);
        if ($entity == null) {
            return null;
        }
        $entity->setRecoverTime(new \DateTime('2000-01-01'));
        $entity->setLoginPassword($newPassword);
        $this->saveEntity($entity);

        return $entity;
    }

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        $query = $query->andWhere('tbl.emailAddress <> :emailRoot')->setParameter(':emailRoot', 'vcqviet@gmail.com');
        if (isset($data['role_id']) && intval($data['role_id']) > 0) {
            $query = $query->leftJoin('tbl.cmsRoles', 'role')->andWhere('role.id = :role_id')->setParameter(':role_id', $data['role_id']);
        }
        return parent::customQuery($data, $query);
    }
}
