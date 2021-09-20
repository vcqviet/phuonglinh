<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * CmsIpLock
 *
 * @ORM\Table(name="cms_ip_locks")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CmsIpLockRepository")
 *
 */
class CmsIpLock extends MasterEntity
{

    /**
     *
     * @var string
     *
     * @ORM\Column(name="ip_locked", type="string", nullable=true, length=255)
     */
    private $ipLocked;

    public function getIpLocked(): string
    {
        return strval($this->ipLocked);
    }

    public function setIpLocked($val): self
    {
        $this->ipLocked = strval($val);

        return $this;
    }


    public function __construct()
    {
        parent::__construct();
    }
}