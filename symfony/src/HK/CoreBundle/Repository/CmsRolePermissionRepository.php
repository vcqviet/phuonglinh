<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;
use HK\CoreBundle\Entity\CmsRole;
use HK\CoreBundle\Entity\CmsRolePermission;

class CmsRolePermissionRepository extends MasterRepository
{

    public function addOrUpdate($roleId, $module, $level)
    {
        $role = $this->getEntityManager()
            ->getRepository(CmsRole::class)
            ->bkGetById($roleId);
        if ($role == null) {
            return false;
        }
        $permission = $this->findOneBy([
            'cmsRoleId' => $roleId,
            'moduleName' => $module
        ]);
        if ($permission == null) {
            $permission = new CmsRolePermission();
            $permission->setCmsRole($role);
            $permission->setModuleName($module);
        }
        if($permission->isGranted($level)){
            $permission->removeGranted($level);
        } else {
            $permission->setGranted($level);
        }
        return $this->saveEntity($permission);
    }

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        return parent::customQuery($data, $query);
    }
}