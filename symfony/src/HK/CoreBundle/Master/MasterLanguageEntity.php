<?php
namespace HK\CoreBundle\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\MappedSuperclass
 *
 */
class MasterLanguageEntity
{

    protected $parent;

    public function setParent($parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="ref_id", type="integer", nullable=true)
     */
    protected $refId;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId(): int
    {
        return intval($this->id);
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="seo_url", type="string", length=255, nullable=true)
     */
    protected $seoUrl;

    public function getSeoUrl(): string
    {
        return strval($this->seoUrl);
    }

    public function setSeoUrl($val): self
    {
        $this->seoUrl = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
     */
    protected $seoTitle;

    public function getSeoTitle(): string
    {
        return strval($this->seoTitle);
    }

    public function setSeoTitle($val): self
    {
        $this->seoTitle = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="seo_description", type="string", length=255, nullable=true)
     */
    protected $seoDescription;

    public function getSeoDescription(): string
    {
        return strval($this->seoDescription);
    }

    public function setSeoDescription($val): self
    {
        $this->seoDescription = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lang", type="string", nullable=false, length=20)
     */
    protected $lang;

    public function getLang(): string
    {
        return strval($this->lang);
    }

    public function setLang($val): self
    {
        $this->lang = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=true, length=255)
     */
    protected $title;

    public function getTitle(): string
    {
        return strval($this->title);
    }

    public function setTitle($val): self
    {
        $this->title = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    public function getDescription(): string
    {
        return strval($this->description);
    }

    public function setDescription($val): self
    {
        $this->description = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    public function getContent(): string
    {
        return strval($this->content);
    }

    public function setContent($val): self
    {
        $this->content = strval($val);
        return $this;
    }

    public function __construct()
    {}
}
