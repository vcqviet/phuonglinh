<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SettingWebsite
 *
 * @ORM\Table(name="hksetting_websites")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\SettingWebsiteRepository")
 *
 */
class SettingWebsite extends MasterEntity
{

    public static $_GOOGLE_FILE_URL_IMPORT = '_GOOGLE_FILE_URL_IMPORT';
    public static $_GOOGLE_FILE_URL_ERROR = '_GOOGLE_FILE_URL_ERROR';

    public static $_KEY_SMTP_USER = '_SMTP_USER';
    public static $_KEY_SMTP_PASSWORD = '_SMTP_PASSWORD';
    public static $_KEY_SMTP_HOST = '_SMTP_HOST';
    public static $_KEY_SMTP_PORT = '_SMTP_PORT';
    public static $_KEY_SMTP_TYPE = '_SMTP_TYPE';

    public static $_TYPE_TEXT = '_TEXT';
    public static $_TYPE_PASSWORD = '_PASSWORD';
    public static $_TYPE_RADIO = '_RADIO';

    /**
     *
     * @var string
     *
     * @ORM\Column(name="attribute", type="string", nullable=true, length=512)
     */
    private $attribute;

    public function getAttribute(): array
    {
        if (empty($this->attribute)) {
            return [];
        }
        return unserialize(strval($this->attribute));
    }

    public function setAttribute($val): self
    {
        if (empty($val)) {
            $val = [];
        }
        $this->attribute = strval(serialize($val));

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="noted", type="string", nullable=true, length=255)
     */
    private $noted;

    public function getNoted(): string
    {
        return strval($this->noted);
    }

    public function setNoted($val): self
    {
        $this->noted = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true, length=65535)
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
     * @ORM\Column(name="type", type="string", nullable=true, length=255)
     */
    private $type;

    public function getType(): string
    {
        return strval($this->type);
    }

    public function setType($val): self
    {
        $this->type = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name_key", type="string", nullable=true, length=255)
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
     * @var SettingWebsiteCategory
     * @ORM\ManyToOne(targetEntity="SettingWebsiteCategory", inversedBy="settings", cascade={"persist"})
     * @ORM\JoinColumn(name="cate_id", referencedColumnName="id")
     */
    private $cate;

    public function setCate(SettingWebsiteCategory $parent = null)
    {
        $this->cate = $parent;
        return $this;
    }

    public function getCate()
    {
        return $this->cate;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="cate_id", type="integer", nullable=true)
     */
    private $cateId;

    /**
     *
     * @var SettingWebsiteOption[]
     * @ORM\OneToMany(targetEntity="SettingWebsiteOption", mappedBy="setting", cascade={"persist"})
     */
    private $options;

    public function addOption(SettingWebsiteOption $entity): self
    {
        $entity->setSetting($this);
        $this->options[] = $entity;
        return $this;
    }

    public function removeOption(SettingWebsiteOption $entity): self
    {
        $this->options->removeElement($entity);
        return $this;
    }

    public function getOptions($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        return $this->getArrayItems($this->options, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
    }

    public function __construct()
    {
        $this->options = new ArrayCollection();
        parent::__construct();
    }
}