<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CmsRole
 *
 * @ORM\Table(name="cms_roles")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CmsRoleRepository")
 *
 */
class CmsRole extends MasterEntity
{
    public static $_ROLE_ADMIN = 'ADMIN';
    /**
     *
     * @var string
     *
     * @ORM\Column(name="role_name", type="string", nullable=false, length=255, unique=true)
     */
    private $roleName;

    public function getRoleName(): string
    {
        return strval($this->roleName);
    }

    public function setRoleName($val): self
    {
        $this->roleName = strval($val);

        return $this;
    }

    /**
     *
     * @var CmsRolePermission
     * @ORM\OneToMany(targetEntity="CmsRolePermission", mappedBy="cmsRole", cascade={"persist"})
     */
    private $cmsRolePermissions;

    public function addCmsRolePermission(CmsRolePermission $entity): self
    {
        $entity->setCmsRole($this);
        $this->cmsRolePermisstions[] = $entity;
        return $this;
    }

    public function removeCmsRolePermission(CmsRolePermission $entity): self
    {
        $this->cmsRolePermisstions->removeElement($entity);
        return $this;
    }

    public function getCmsRolePermissions($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        return $this->getArrayItems($this->cmsRolePermissions, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
    }

    /**
     *
     * @var CmsUser
     * @ORM\ManyToMany(targetEntity="CmsUser", mappedBy="cmsRoles", cascade={"persist"})
     */
    private $cmsUsers;

    public function addCmsUser(CmsUser $entity): self
    {
        $this->cmsUsers[] = $entity;
        return $this;
    }

    public function removeCmsUser(CmsUser $entity): self
    {
        $this->cmsUsers->removeElement($entity);
        return $this;
    }

    public function getCmsUsers($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        return $this->getArrayItems($this->cmsUsers, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
    }

    public function __construct()
    {
        $this->cmsUsers = new ArrayCollection();
        $this->cmsRolePermisstions = new ArrayCollection();
        
        parent::__construct();
    }
}