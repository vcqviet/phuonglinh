<?php

namespace HK\CoreBundle\Master;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Helper\StringHelper;

/**
 *
 * @ORM\MappedSuperclass
 *
 */
class MasterEntity
{
    protected ArrayCollection $langContents;

    public function addLangContent($entity): self
    {
        $entity->setParent($this);
        $this->langContents[] = $entity;
        return $this;
    }

    public function removeLangContent($entity): self
    {
        $this->langContents->removeElement($entity);
        return $this;
    }

    public function getLangContents()
    {
        return $this->langContents;
    }

    public function getLangContent($lang = '')
    {
        $data = $this->getLangContents();
        if (count($data) == 0) {
            return null;
        }
        $lang = Configuration::instance()->getLanguage($lang);
        foreach ($data as $itemLang) {
            if ($itemLang->getLang() === $lang) {
                return $itemLang;
            }
        }
        return null;
    }

    public function getTitle($lang = ''): string
    {
        $lang = $this->getLangContent($lang);
        if ($lang != null) {
            return $lang->getTitle();
        }
        return '';
    }

    public function setTitle($val)
    {
        return $this;
    }

    public function getDescription($lang = ''): string
    {
        $lang = $this->getLangContent($lang);
        if ($lang != null) {
            return $lang->getDescription();
        }
        return '';
    }

    public function setDescription($val)
    {
        return $this;
    }

    public function getContent($lang = ''): string
    {
        $lang = $this->getLangContent($lang);
        if ($lang != null) {
            return $lang->getContent();
        }
        return '';
    }

    public function setContent($val)
    {
        return $this;
    }

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

    public function getEditId(): int
    {
        return intval($this->id);
    }

    public function setEditId($id): self
    {
        return $this;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", length=20, nullable=false)
     */
    protected $displayOrder = 0;

    public function getDisplayOrder(): int
    {
        return intval($this->displayOrder);
    }

    public function setDisplayOrder($val): self
    {
        $this->displayOrder = intval($val);
        return $this;
    }

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="view_counter", type="integer", length=20, nullable=true)
     */
    protected $viewCounter = 0;

    public function getViewCounter(): int
    {
        return intval($this->viewCounter);
    }

    public function setViewCounter($val): self
    {
        $this->viewCounter = intval($val);
        return $this;
    }

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    protected $isDeleted = false;

    public function getIsDeleted(): bool
    {
        return boolval($this->isDeleted);
    }

    public function setIsDeleted($val): self
    {
        $this->isDeleted = boolval($val);
        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=255, nullable=true)
     */
    protected $createdBy;

    public function getCreatedBy(): string
    {
        return strval($this->createdBy);
    }

    public function setCreatedBy($val): self
    {
        $this->createdBy = strval($val);

        return $this;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="updated_by", type="string", length=255, nullable=true)
     */
    protected $updatedBy;

    public function getUpdatedBy(): string
    {
        return strval($this->updatedBy);
    }

    public function setUpdatedBy($val): self
    {
        $this->updatedBy = strval($val);

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt($val): self
    {
        $this->createdAt = $val;

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($val): self
    {
        $this->updatedAt = $val;

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($val): self
    {
        $this->deletedAt = $val;

        return $this;
    }

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
     */
    protected $isPublished = true;

    public function getIsPublished(): bool
    {
        return boolval($this->isPublished);
    }

    public function setIsPublished($val): self
    {
        $this->isPublished = boolval($val);
        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="published_from_at", type="datetime", nullable=true)
     */
    protected $publishedFromAt;

    public function getPublishedFromAt(): ?\DateTime
    {
        return $this->publishedFromAt;
    }

    public function setPublishedFromAt($val): self
    {
        $this->publishedFromAt = $val;

        return $this;
    }

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="published_to_at", type="datetime", nullable=true)
     */
    protected $publishedToAt;

    public function getPublishedToAt(): ?\DateTime
    {
        return $this->publishedToAt;
    }

    public function setPublishedToAt($val): self
    {
        $this->publishedToAt = $val;

        return $this;
    }


    protected function getArrayItems($entities = [], $isPublished = true, $publishedFromAt = null, $publishedToAt = null, $isDeleted = false): array
    {
        $returnEntities = [];
        foreach ($entities as $item) {
            if (($isDeleted || !$item->getIsDeleted()) && (!$isPublished || $item->getIsPublished()) && ($publishedFromAt == null || $item->getPublishedFromAt() <= $publishedFromAt) && ($publishedToAt == null || $item->getPublishedToAt() >= $publishedToAt)) {
                $returnEntities[] = $item;
            }
        }
        return $returnEntities;
    }

    protected $hiddenPhoto = '';

    public function getHiddenPhoto(): string
    {
        return '';
    }

    public function setHiddenPhoto($val): self
    {
        return $this;
    }
    /**
     *
     * @var string
     *
     * @ORM\Column(name="photo_url", type="string", length=255, nullable=true)
     */
    protected $photoUrl;

    public function getPhotoUrl(): string
    {
        return strval($this->photoUrl);
    }

    public function setPhotoUrl($val): self
    {
        $this->photoUrl = strval($val);
        return $this;
    }
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        $this->setUpdatedAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        $this->setCreatedBy('Anonymouse');
        $this->setUpdatedBy('Anonymouse');
        $this->setDisplayOrder(0);
        $this->setIsDeleted(false);
        $this->setIsPublished(true);

        $this->langContents = new ArrayCollection();
    }

    public function getUrlForSeo($lang = '')
    {
        $lang = $this->getLangContent($lang);
        if ($lang == null) {
            return '';
        }
        if (!empty($lang->getSeoUrl())) {
            return $lang->getSeoUrl();
        }
        return StringHelper::encodeTitle($this->getTitle());
    }
}
