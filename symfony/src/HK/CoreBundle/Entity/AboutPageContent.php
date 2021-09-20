<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterLanguageEntity;

/**
 * AboutPageContent
 *
 * @ORM\Table(name="hkabout_page_contents")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\AboutPageContentRepository")
 *
 */
class AboutPageContent extends MasterLanguageEntity
{

    /**
     *
     * @var AboutPage
     * @ORM\ManyToOne(targetEntity="AboutPage", inversedBy="langContents", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_id", referencedColumnName="id")
     */
    protected $parent;

    public function __construct()
    {
        parent::__construct();
    }
}