<?php
namespace HK\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * AboutPage
 *
 * @ORM\Table(name="hkabout_pages")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\AboutPageRepository")
 *
 */
class AboutPage extends MasterEntity
{

    public static $_ABOUT = '_ABOUT';

    /**
     *
     * @var AboutPageContent
     * @ORM\OneToMany(targetEntity="AboutPageContent", mappedBy="parent", cascade={"persist"})
     */
    protected ArrayCollection $langContents;

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
     * @ORM\Column(name="menu_position", type="string", nullable=true, length=255)
     */
    private $menuPosition;

    public function getMenuPosition(): string
    {
        return strval($this->menuPosition);
    }

    public function setMenuPosition($val): self
    {
        $this->menuPosition = strval($val);
        
        return $this;
    }

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_menu", type="boolean", nullable=false)
     */
    private $isMenu = false;

    public function getIsMenu(): bool
    {
        return boolval($this->isMenu);
    }

    public function setIsMenu($val): self
    {
        $this->isMenu = boolval($val);
        return $this;
    }

    public function __construct()
    {
        parent::__construct();
    }
}