<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * SettingMailTemplate
 *
 * @ORM\Table(name="hksetting_mail_templates")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\SettingMailTemplateRepository")
 *
 */
class SettingMailTemplate extends MasterEntity
{

    public static $_REGISTER = '_REGISTER';

    public static $_CONTACT = '_CONTACT';

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name_key", type="string", nullable=true, length=255, unique=true)
     */
    private $nameKey;

    public function getNameKey(): string
    {
        return strval($this->nameKey);
    }

    public function setNameKey($val): self
    {
        $this->nameKey = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true, length=255)
     */
    private $name;

    public function getName(): string
    {
        return strval($this->name);
    }

    public function setName($val): self
    {
        $this->name = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=true, length=255)
     */
    private $email;

    public function getEmail(): string
    {
        return strval($this->email);
    }

    public function setEmail($val): self
    {
        $this->email = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="copy_to", type="string", nullable=true, length=255)
     */
    private $copyTo;

    public function getCopyTo(): string
    {
        return strval($this->copyTo);
    }

    public function setCopyTo($val): self
    {
        $this->copyTo = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="attachment", type="string", nullable=true, length=255)
     */
    private $attachment;

    public function getAttachment(): string
    {
        return strval($this->attachment);
    }

    public function setAttachment($val): self
    {
        $this->attachment = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="blind_copy_to", type="string", nullable=true, length=255)
     */
    private $blindCopyTo;

    public function getBlindCopyTo(): string
    {
        return strval($this->blindCopyTo);
    }

    public function setBlindCopyTo($val): self
    {
        $this->blindCopyTo = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="subject", type="string", nullable=true, length=255, unique=true)
     */
    private $subject;

    public function getSubject(): string
    {
        return strval($this->subject);
    }

    public function setSubject($val): self
    {
        $this->subject = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true, length=65535)
     */
    private $content;

    public function getContent($lang = ''): string
    {
        return strval($this->content);
    }

    public function setContent($val)
    {
        $this->content = strval($val);
        
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="content_text", type="text", nullable=true, length=65535)
     */
    private $contentText;

    public function getContentText(): string
    {
        return strval($this->contentText);
    }

    public function setContentText($val)
    {
        $this->contentText = strval($val);
        
        return $this;
    }

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_stopped", type="boolean", nullable=false)
     */
    protected $isStopped;

    public function getIsStopped(): bool
    {
        return boolval($this->isStopped);
    }

    public function setIsStopped($val): self
    {
        $this->isStopped = boolval($val);
        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setIsStopped(false);
    }
}