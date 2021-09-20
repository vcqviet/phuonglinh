<?php
namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterLanguageEntity;

/**
 * TrackingInfoContent
 *
 * @ORM\Table(name="hktracking_info_contents")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\TrackingInfoContentRepository")
 *
 */
class TrackingInfoContent extends MasterLanguageEntity
{

    /**
     *
     * @var TrackingInfo
     * @ORM\ManyToOne(targetEntity="TrackingInfo", inversedBy="langContents", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_id", referencedColumnName="id")
     */
    protected $parent;
    public function __construct()
    {
        parent::__construct();
    }
}