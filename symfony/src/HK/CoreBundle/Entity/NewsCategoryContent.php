<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterLanguageEntity;

/**
 * NewsCategoryContent
 *
 * @ORM\Table(name="hknews_category_contents")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\NewsCategoryContentRepository")
 *
 */
class NewsCategoryContent extends MasterLanguageEntity
{

    /**
     *
     * @var NewsCategory
     * @ORM\ManyToOne(targetEntity="NewsCategory", inversedBy="langContents", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_id", referencedColumnName="id")
     */
    protected $parent;
    public function __construct()
    {
        parent::__construct();
    }
}