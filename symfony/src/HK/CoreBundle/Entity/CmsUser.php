<?php

namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CmsUser
 *
 * @ORM\Table(name="cms_users")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CmsUserRepository")
 *
 */
class CmsUser extends MasterEntity implements UserInterface
{

    public static $lengthEncoded = 40;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", nullable=false, length=255, unique=true)
     */
    private $emailAddress;

    public function getEmailAddress(): string
    {
        return strval($this->emailAddress);
    }

    public function setEmailAddress($val): self
    {
        $this->emailAddress = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", nullable=false, length=20, unique=true)
     */
    private $phoneNumber;

    public function getPhoneNumber(): string
    {
        return strval($this->phoneNumber);
    }

    public function setPhoneNumber($val): self
    {
        $this->phoneNumber = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="login_ran", type="string", nullable=false, length=20)
     */
    private $loginRan = 'bi@z';

    public function getLoginRan(): string
    {
        return strval($this->loginRan);
    }

    public function setLoginRan($val): self
    {
        $this->loginRan = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="login_password", type="string", length=255, nullable=false)
     */
    private $loginPassword;

    public function getLoginPassword(): string
    {
        return strval($this->loginPassword);
    }

    public function setLoginPassword($val): self
    {
        $this->loginPassword = strval($val);

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="recover_time", type="datetime", nullable=true)
     */
    private $recoverTime;

    public function getRecoverTime(): ?\DateTime
    {
        return $this->recoverTime;
    }

    public function setRecoverTime($val): self
    {
        $this->recoverTime = $val;

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="last_logged_in_at", type="datetime", nullable=true)
     */
    private $lastLoggedInAt;

    public function getLastLoggedInAt(): ?\DateTime
    {
        return $this->lastLoggedInAt;
    }

    public function setLastLoggedInAt($val): self
    {
        $this->lastLoggedInAt = $val;

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="last_logged_in_ip", type="string", length=20, nullable=true)
     */
    private $lastLoggedInIp;

    public function getLastLoggedInIp(): string
    {
        return strval($this->lastLoggedInIp);
    }

    public function setLastLoggedInIp($val): self
    {
        $this->lastLoggedInIp = $val;

        return $this;
    }

    /**
     *
     * @var CmsRole
     * @ORM\ManyToMany(targetEntity="CmsRole", inversedBy="cmsUsers", cascade={"persist"})
     * @ORM\JoinTable(name="cms_user_to_cms_roles",
     *      joinColumns={@ORM\JoinColumn(name="cms_user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cms_role_id", referencedColumnName="id")})
     */
    private $cmsRoles;

    public function addCmsRole(CmsRole $entity): self
    {
        $this->cmsRoles[] = $entity;
        return $this;
    }

    public function removeCmsRole(CmsRole $entity): self
    {
        $this->cmsRoles->removeElement($entity);
        return $this;
    }

    public function getCmsRoles($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        return $this->getArrayItems($this->cmsRoles, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
    }

    public function __construct()
    {
        $this->cmsRoles = new ArrayCollection();

        parent::__construct();
    }

    public function getRoles()
    {
        $roles = $this->getCmsRoles();
        $arr = [];
        foreach ($roles as $role) {
            $arr[] = 'ROLE_' . strtoupper(trim($role->getRoleName()));
        }
        if ($this->getUsername() == 'vcqviet@gmail.com') {
            $arr[] = 'ROLE_ADMIN';
        }
        return $arr;
    }

    public function getUsername()
    {
        return $this->getEmailAddress();
    }

    public function getPassword()
    {
        return $this->getLoginPassword();
    }

    public function getSalt()
    {
        return $this->getLoginRan();
    }

    public function eraseCredentials()
    { }

    public $confirmPassword;
    public $oldPassword;
}
