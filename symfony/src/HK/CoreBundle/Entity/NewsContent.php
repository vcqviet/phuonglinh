<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterLanguageEntity;

/**
 * NewsContent
 *
 * @ORM\Table(name="hknews_contents")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\NewsContentRepository")
 *
 */
class NewsContent extends MasterLanguageEntity
{

    /**
     *
     * @var News
     * @ORM\ManyToOne(targetEntity="News", inversedBy="langContents", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_id", referencedColumnName="id")
     */
    protected $parent;
    public function __construct()
    {
        parent::__construct();
    }
}