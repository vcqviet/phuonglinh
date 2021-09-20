<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * SettingWebsiteOption
 *
 * @ORM\Table(name="hksetting_website_options")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\SettingWebsiteOptionRepository")
 *
 */
class SettingWebsiteOption extends MasterEntity
{
    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false)
     */
    protected $isDefault = false;
    
    public function getIsDefault(): bool
    {
        return boolval($this->isDefault);
    }
    
    public function setIsDefault($val): self
    {
        $this->isDefault = boolval($val);
        return $this;
    }
    /**
     *
     * @var string
     *
     * @ORM\Column(name="value", type="string", nullable=true, length=255)
     */
    private $value;

    public function getValue(): string
    {
        return strval($this->value);
    }

    public function setValue($val): self
    {
        $this->value = strval($val);

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
     * @var SettingWebsite
     * @ORM\ManyToOne(targetEntity="SettingWebsite", inversedBy="options", cascade={"persist"})
     * @ORM\JoinColumn(name="setting_id", referencedColumnName="id")
     */
    private $setting;

    public function setSetting(SettingWebsite $parent = null)
    {
        $this->setting = $parent;
        return $this;
    }

    public function getSetting()
    {
        return $this->setting;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="setting_id", type="integer", nullable=true)
     */
    private $settingId;

    public function __construct()
    {
        parent::__construct();
    }
}