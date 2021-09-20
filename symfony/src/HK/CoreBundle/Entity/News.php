<?php

namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * News
 *
 * @ORM\Table(name="hknews")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\NewsRepository")
 *
 */
class News extends MasterEntity
{
    public static $_SHOW_ON_ALL = '_SHOW_ON_ALL';
    public static $_SHOW_ON_WEB = '_SHOW_ON_WEB';
    public static $_SHOW_ON_APP = '_SHOW_ON_APP';
    

    /**
     *
     * @ORM\OneToMany(targetEntity="NewsContent", mappedBy="parent", cascade={"persist"})
     */
    protected ArrayCollection $langContents;

    /**
     *
     * @var NewsCategory
     * @ORM\ManyToOne(targetEntity="NewsCategory", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(name="cate_id", referencedColumnName="id")
     */
    private $cate;

    public function setCate(NewsCategory $parent = null)
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
     * @var string
     *
     * @ORM\Column(name="thumbnail_url", type="string", length=255, nullable=true)
     */
    protected $thumbnailUrl;

    public function getThumbnailUrl(): string
    {
        return strval($this->thumbnailUrl);
    }

    public function setThumbnailUrl($val): self
    {
        $this->thumbnailUrl = strval($val);
        return $this;
    }


    /**
     *
     * @var string
     *
     * @ORM\Column(name="viewmore_url", type="string", length=255, nullable=true)
     */
    protected $viewmoreUrl;

    public function getViewmoreUrl(): string
    {
        return strval($this->viewmoreUrl);
    }

    public function setViewmoreUrl($val): self
    {
        $this->viewmoreUrl = strval($val);
        return $this;
    }
    /**
     *
     * @var string
     *
     * @ORM\Column(name="show_on", type="string", length=255, nullable=true)
     */
    protected $showOn;

    public function getShowOn(): string
    {
        return strval($this->showOn);
    }

    public function setShowOn($val): self
    {
        $this->showOn = strval($val);
        return $this;
    }
    public function __construct()
    {
        parent::__construct();
    }
}
