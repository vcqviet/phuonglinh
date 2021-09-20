<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * HomeSlider
 *
 * @ORM\Table(name="home_sliders")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\HomeSliderRepository")
 *
 */
class HomeSlider extends MasterEntity
{

    /**
     *
     * @var HomeSliderContent
     * @ORM\OneToMany(targetEntity="HomeSliderContent", mappedBy="parent", cascade={"persist"})
     */
    protected ArrayCollection $langContents;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reference_url", type="string", nullable=true, length=255)
     */
    private $referenceUrl;

    public function getReferenceUrl(): string
    {
        return strval($this->referenceUrl);
    }

    public function setReferenceUrl($val): self
    {
        $this->referenceUrl = strval($val);

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
    }
}