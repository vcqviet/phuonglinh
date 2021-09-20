<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * NewsCategory
 *
 * @ORM\Table(name="hknews_categories")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\NewsCategoryRepository")
 *
 */
class NewsCategory extends MasterEntity
{


    /**
     *
     * @var NewsCategoryContent
     * @ORM\OneToMany(targetEntity="NewsCategoryContent", mappedBy="parent", cascade={"persist"})
     */
    protected ArrayCollection $langContents;
    /**
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="cate", cascade={"persist"})
     */
    private $news;
    
    public function addNews(News $entity): self
    {
        $entity->setCate($this);
        $this->news[] = $entity;
        return $this;
    }
    
    public function removeNews(News $entity): self
    {
        $this->news->removeElement($entity);
        return $this;
    }
    
    public function getNews($isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        return $this->getArrayItems($this->news, $isPublished, $publishedFromAt, $publishedToAt, $isDeleted);
    }

    public function __construct()
    {
        $this->news = new ArrayCollection();
        parent::__construct();
    }
}