<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterLanguageEntity;

/**
 * HomeSliderContent
 *
 * @ORM\Table(name="home_slider_contents")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\HomeSliderContentRepository")
 *
 */
class HomeSliderContent extends MasterLanguageEntity
{

    /**
     *
     * @var HomeSlider
     * @ORM\ManyToOne(targetEntity="HomeSlider", inversedBy="langContents", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_id", referencedColumnName="id")
     */
    protected $parent;
    public function __construct()
    {
        parent::__construct();
    }
}