<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * CmsRolePermission
 *
 * @ORM\Table(name="cms_role_permissions")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CmsRolePermissionRepository")
 *
 */
class CmsRolePermission extends MasterEntity
{

    public static $LEVEL_VIEW = '_view';

    public static $LEVEL_EDIT = '_edit';

    public static $LEVEL_DELETE = '_delete';

    /**
     *
     * @var string
     *
     * @ORM\Column(name="module_name", type="string", nullable=true, length=255)
     */
    private $moduleName;

    public function getModuleName(): string
    {
        return strval($this->moduleName);
    }

    public function setModuleName($val): self
    {
        $this->moduleName = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="url_action", type="string", nullable=true, length=255)
     */
    private $urlAction;

    public function getUrlAction(): string
    {
        return strval($this->urlAction);
    }

    public function setUrlAction($val): self
    {
        $this->urlAction = strval($val);

        return $this;
    }

    public function isGranted($level): bool
    {
        if ($this->cmsRole->getRoleName() === CmsRole::$_ROLE_ADMIN || $this->cmsRole->getRoleName() === 'ROOT') {
            return true;
        }
        return in_array($level, $this->getAccessRight());
    }

    public function setGranted($level): self
    {
        if (! $this->isGranted($level)) {
            $access = $this->getAccessRight();
            $access[] = $level;
            $this->setAccessRight($access);
        }
        return $this;
    }

    public function removeGranted($level): self
    {
        if ($this->isGranted($level)) {
            $access = $this->getAccessRight();
            foreach($access as $key => $acc) {
                if($acc === $level) {
                    unset($access[$key]);
                    break;
                }
            }
            $this->setAccessRight($access);
        }
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="access_right", type="string", nullable=true, length=255)
     */
    private $accessRight;

    public function getAccessRight(): array
    {
        $access = strval($this->accessRight);
        if (empty($access)) {
            return [];
        }
        try {
            return unserialize($access);
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function setAccessRight($val): self
    {
        if (empty($val)) {
            $val = [];
        }
        $this->accessRight = serialize($val);

        return $this;
    }

    /**
     *
     * @var CmsRole
     * @ORM\ManyToOne(targetEntity="CmsRole", inversedBy="cmsRolePermissions", cascade={"persist"})
     * @ORM\JoinColumn(name="cms_role_id", referencedColumnName="id")
     */
    private $cmsRole;

    public function setCmsRole(CmsRole $parent = null)
    {
        $this->cmsRole = $parent;
        return $this;
    }

    public function getCmsRole()
    {
        return $this->cmsRole;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="cms_role_id", type="integer", nullable=true)
     */
    private $cmsRoleId;

    public function __construct()
    {
        parent::__construct();
    }
}