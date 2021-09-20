<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;
use HK\CoreBundle\Helper\StringHelper;
use HK\CoreBundle\Helper\NumberHelper;

/**
 * SettingWebsiteCategory
 *
 * @ORM\Table(name="hksetting_website_categories")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\SettingWebsiteCategoryRepository")
 *
 */
class SettingWebsiteCategory extends MasterEntity
{

    public static $_TYPE_CUSTOM = '_CUSTOM';

    public static $_TYPE_GENERAL = '_GENERAL';

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
     * @var SettingWebsite[]
     * @ORM\OneToMany(targetEntity="SettingWebsite", mappedBy="cate", cascade={"persist"})
     */
    private $settings;

    public function addSetting(SettingWebsite $entity): self
    {
        $entity->setCate($this);
        $this->settings[] = $entity;
        return $this;
    }

    public function removeSetting(SettingWebsite $entity): self
    {
        $this->settings->removeElement($entity);
        return $this;
    }

    public function getSettings($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        $arr = $this->getArrayItems($this->settings, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
        $arrSorts = [];
        foreach ($arr as $ar) {
            $arrSorts[NumberHelper::getStringNumber($ar->getDisplayOrder(), 10)] = $ar;
        }
        krsort($arrSorts, false);
        $rt = [];
        foreach ($arrSorts as $ar) {
            $rt[] = $ar;
        }
        return $rt;
    }

    public function __construct()
    {
        $this->settings = new ArrayCollection();
        parent::__construct();
    }
}