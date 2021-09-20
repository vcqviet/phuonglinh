<?php

namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TrackingInfo
 *
 * @ORM\Table(name="hktracking_infos")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\TrackingInfoRepository")
 *
 */
class TrackingInfo extends MasterEntity
{

    public static $_GENDER_MALE = '_GENDER_MALE';
    public static $_GENDER_FEMALE = '_GENDER_FEMALE';


    /**
     *
     * @ORM\OneToMany(targetEntity="TrackingInfoContent", mappedBy="parent", cascade={"persist"})
     */
    protected ArrayCollection $langContents;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    protected $gender;

    public function getGender(): string
    {
        return strval($this->gender);
    }

    public function setGender($val): self
    {
        $this->gender = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", length=255, nullable=true)
     */
    protected $deviceId;

    public function getDeviceId(): string
    {
        return strval($this->deviceId);
    }

    public function setDeviceId($val): self
    {
        $this->deviceId = strval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=255, nullable=true)
     */
    protected $platform;

    public function getPlatform(): string
    {
        return strval($this->platform);
    }

    public function setPlatform($val): self
    {
        $this->platform = strval($val);
        return $this;
    }

    public function __construct()
    {
        parent::__construct();
    }
}
