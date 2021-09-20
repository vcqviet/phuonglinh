<?php

namespace HK\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HK\CoreBundle\Master\MasterEntity;

/**
 * Customer
 *
 * @ORM\Table(name="hkcustomers")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HK\CoreBundle\Repository\CustomerRepository")
 *
 */
class Customer extends MasterEntity
{


    /**
     *
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private ?string $emailAddress;

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress ?? '';
    }

    public function setEmailAddress(?string $val)
    {
        $this->emailAddress = $val;
    }

    /**
     *
     * @ORM\Column(type="string", nullable=true, length=512)
     */
    private ?string $address;

    public function getAddress(): ?string
    {
        return $this->address ?? '';
    }

    public function setAddress(?string $val)
    {
        $this->address = $val;
    }


    /**
     *
     * @ORM\Column(type="string", nullable=false, length=20)
     */
    private ?string $phoneNumber;

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber ?? '';
    }

    public function setPhoneNumber(?string $val)
    {
        $this->phoneNumber = $val;
    }

    /**
     *
     * @ORM\Column(type="string", nullable=false, length=20)
     */
    private ?string $fullName;

    public function getFullName(): ?string
    {
        return $this->fullName ?? '';
    }

    public function setFullName(?string $val)
    {
        $this->fullName = $val;
    }

    /**
     *
     * @ORM\Column(type="string", nullable=false, length=20)
     */
    private ?string $productModel;

    public function getProductModel(): ?string
    {
        return $this->productModel ?? '';
    }

    public function setProductModel(?string $val)
    {
        $this->productModel = $val;
    }

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $dateOfBirth;

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTime $val)
    {
        $this->dateOfBirth = $val;
    }

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $orderDate;

    public function getOrderDate(): ?\DateTime
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTime $val)
    {
        $this->orderDate = $val;
    }

    public function __construct()
    {
        parent::__construct();
    }
}
