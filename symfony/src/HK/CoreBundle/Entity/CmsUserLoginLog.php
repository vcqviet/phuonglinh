<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * CmsUserLogin
 *
 * @ORM\Table(name="cms_user_login_logs")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CmsUserLoginLogRepository")
 *
 */
class CmsUserLoginLog extends MasterEntity
{

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="login_at", type="datetime", nullable=true)
     */
    private $loginAt;

    public function getLoginAt(): ?\DateTime
    {
        return $this->loginAt;
    }

    public function setLoginAt($val): self
    {
        $this->loginAt = $val;

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="login_ip", type="string", nullable=false, length=255)
     */
    private $loginIp;

    public function getLoginIp(): string
    {
        return strval($this->loginIp);
    }

    public function setLoginIp($val): self
    {
        $this->loginIp = strval($val);

        return $this;
    }
    
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", nullable=false, length=255)
     */
    private $userName;
    
    public function getUserName(): string
    {
        return strval($this->userName);
    }
    
    public function setUserName($val): self
    {
        $this->userName = strval($val);
        
        return $this;
    }
    
    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_success", type="boolean", nullable=false)
     */
    private $isSuccess = false;
    
    public function getIsSuccess(): bool
    {
        return boolval($this->isSuccess);
    }
    
    public function setIsSuccess($val): self
    {
        $this->isSuccess = boolval($val);
        return $this;
    }
}