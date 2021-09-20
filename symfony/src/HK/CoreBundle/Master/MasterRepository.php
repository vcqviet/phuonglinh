<?php

namespace HK\CoreBundle\Master;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HK\CoreBundle\Helper\DateTimeHelper;
use Doctrine\ORM\QueryBuilder;
use HK\CoreBundle\Helper\DisplayOrderHelper;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Helper\StringHelper;

class MasterRepository extends EntityRepository
{
    protected $hasContent = false;

    private $dataTemp = [];

    public function setDefaultOrder($entity)
    {
        $entity->setDisplayOrder($entity->getId());
        return $this->saveEntityNoneUpdate($entity);
    }
    public function getBySeoUrl($seoUrl, $lang = '')
    {
        return $this->bkGetBySeoUrl($seoUrl, 1);
    }
    public function bkGetBySeoUrl($seoUrl, $isPublished = -1, $lang = '')
    {
        if (empty($seoUrl)) {
            return null;
        }
        $lang = Configuration::instance()->getLanguage($lang);
        $query = $this->createQueryBuilder('tbl')
            ->where('tbl.id > 0')
            ->select('tbl')
            ->orderBy('tbl.isDeleted', 'ASC');
        if (!$this->hasContent) {
            return null;
        }
        $query = $query->leftJoin('tbl.langContents', 'lgc')
            ->andWhere('lgc.seoUrl = :seoUrl')
            ->setParameter(':seoUrl', $seoUrl);
        if ($isPublished != -1) {
            $query = $query->andWhere('tbl.isPublished = :isPublished')->setParameter(':isPublished', $isPublished);
        }
        $paginator = new Paginator($query, true);
        $returnedData = [];
        foreach ($paginator as $item) {
            $returnedData[] = $item;
        }
        if (count($returnedData)) {
            return $returnedData[0];
        }
        return null;
    }

    public function saveEntity($entity, $dataContent = [])
    {
        if (!isset($entity) || $entity === null) {
            return false;
        }
        if ($entity->getId() > 0) {
            $entity->setUpdatedAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        }
        if ($entity->getIsDeleted()) {
            $entity->setDeletedAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        }
        $this->getEntityManager()->beginTransaction();
        try {
            $em = $this->getEntityManager();
            $em->persist($entity);
            $em->flush($entity);

            if ($this->hasContent && isset($dataContent['data']) && count($dataContent['data'])) {
                $dataContent['ref_id'] = $entity->getId();
                if ($this->saveContent($dataContent)) {
                    $this->getEntityManager()->commit();
                    return true;
                }
            }
            $this->getEntityManager()->commit();
            return true;
        } catch (\Exception $ex) { }
        $this->getEntityManager()->rollback();
        return false;
    }

    public function saveEntityNoneUpdate($entity)
    {
        if (!isset($entity) || $entity === null) {
            return false;
        }

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush($entity);
        return true;
    }

    public function saveContent($data)
    {
        try {
            $parent = $this->bkGetById(isset($data['ref_id']) ? $data['ref_id'] : -1);
            if ($parent == null) {
                return false;
            }
            $langs = Configuration::instance()->getAllLanguages();
            foreach ($langs as $lang) {
                if (isset($data['data'][$lang]) && count($data['data'][$lang])) {
                    $entity = $this->getEntityManager()
                        ->getRepository($this->getEntityName() . 'Content')
                        ->findOneBy([
                            'refId' => $parent->getId(),
                            'lang' => $lang
                        ]);
                    if ($entity === null) {
                        $class = $this->getEntityName() . 'Content';
                        $entity = new $class();
                    }
                    foreach ($data['data'][$lang] as $key => $val) {
                        $entity->{'set' . $key}($val);
                        if (strtolower($key) == 'title') {
                            $entity->setSeoUrl(StringHelper::encodeTitle($val));
                        }
                    }
                    $entity->setLang($lang);
                    $entity->setParent($parent);
                    $this->saveEntityNoneUpdate($entity);
                }
            }
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    public function saveDisplayOrder($id, $val)
    {
        $entity = $this->getById($id);
        if (isset($entity) && $entity !== null) {
            $entity->setDisplayOrder($val);
            return $this->saveEntity($entity);
        }
        return false;
    }

    public function getById($id)
    {
        $entity = $this->bkGetById($id, [
            'isPublished' => 1
        ]);
        $now = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        if ($entity == null || ($entity->getPublishedFromAt() != null && $entity->getPublishedFromAt()->format(DateTimeHelper::$DATE_FORMAT) > $now->format(DateTimeHelper::$DATE_FORMAT)) || ($entity->getPublishedToAt() != null && $entity->getPublishedToAt()->format(DateTimeHelper::$DATE_FORMAT) < $now->format(DateTimeHelper::$DATE_FORMAT))) {
            return null;
        }
        return $entity;
    }

    public function getKeyValueId($data = [])
    {
        $dataMapping = $this->getData($data);
        $returnedArray = [];

        foreach ($dataMapping as $item) {
            if (!array_key_exists($item['id'] . '', $item)) {
                $returnedArray[$item['id'] . ''] = $item['name'];
            }
        }
        return $returnedArray;
    }

    public function delete($id = -1)
    {
        return $this->remove($id);
    }

    public function remove($id = -1)
    {
        $entity = $this->find($id);
        if ($entity !== null) {
            return $this->removeEntity($entity);
        }
        return false;
    }

    public function removeEntity($entity)
    {
        if (!isset($entity) || $entity === null) {
            return false;
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        return true;
    }

    public function publish($id = -1)
    {
        $entity = $this->bkGetById($id);
        if (isset($entity) && $entity !== null) {
            $entity->setIsPublished(1);
            return $this->saveEntity($entity);
        }
        return false;
    }

    public function unPublished($id = -1)
    {
        $entity = $this->bkGetById($id);
        if (isset($entity) && $entity !== null) {
            $entity->setIsPublished(0);
            return $this->saveEntity($entity);
        }
        return false;
    }

    public function setOrder($id, $type = 'ASC')
    {
        $entity = $this->bkGetById($id);
        if ($entity != null) {
            $asc = $type == DisplayOrderHelper::$_TYPE_DOWN ? 'DESC' : 'ASC';
            $data = $this->bkGetData([
                'display_orders' => [
                    'displayOrder' => $asc,
                    'id' => $asc
                ],
                'pagination' => [
                    'limit' => 1,
                    'page' => 1
                ],
                'is_set_display_order' => [
                    'order' => $entity->getDisplayOrder(),
                    'type' => $type
                ]
            ]);
            if ($data['total'] > 0) {
                $entity2 = $data['items'][0];
                $order = $entity->getDisplayOrder();
                $entity->setDisplayOrder($entity2->getDisplayOrder());
                $this->saveEntity($entity);
                $entity2->setDisplayOrder($order);
                $this->saveEntity($entity2);
                return true;
            }
            return true;
            // $number = 1;
            // if ($type == DisplayOrderHelper::$_TYPE_DOWN) {
            // $number = - 1;
            // }
            // $entity->setDisplayOrder($entity->getDisplayOrder() + $number);
            // return $this->saveEntity($entity);
        }
        return false;
    }

    public function reversePublish($id = -1)
    {
        $entity = $this->bkGetById($id);
        if (isset($entity) && $entity !== null) {
            $entity->setIsPublished(!$entity->getIsPublished());
            return $this->saveEntity($entity);
        }
        return false;
    }

    public function bkGetById($id, $conditions = [])
    {
        $conditions['id'] = $id;
        $conditions['isDeleted'] = 0;
        return $this->findOneBy($conditions);
    }

    public function customQuery($data, QueryBuilder $q): QueryBuilder
    {
        $this->dataTemp = $data;
        return $q;
    }

    public function getData($data = [])
    {
        $data['is_published'] = 1;
        return $this->bkGetData($data);
    }

    public function bkGetData($data = [])
    {
        $data['is_deleted'] = 1;
        $query = $this->createQueryBuilder('tbl')
            ->where('tbl.id > 0')
            ->select('tbl')
            ->orderBy('tbl.isDeleted', 'ASC');
        if ($this->hasContent) {
            $query = $query->leftJoin('tbl.langContents', 'lgc');
        }
        if (isset($data['not_ids']) && count($data['not_ids'])) {
            $query = $query->andWhere('tbl.id NOT IN (' . implode(',', $data['not_ids']) . ')');
        }
        if (isset($data['ids']) && count($data['ids'])) {
            $query = $query->andWhere('tbl.id  IN (:mids)')->setParameter(':mids', $data['ids']);
        }
        $query = $this->customQuery($data, $query);
        $data = $this->dataTemp;

        if (isset($data['keyword']) && is_array($data['keyword'])) {
            if (!isset($data['keyword']['search_fields']) || count($data['keyword']['search_fields']) <= 0) {
                $data['keyword'] = [
                    'search_fields' => [],
                    'search_by' => ''
                ];
            }
            if (!empty($data['keyword']['search_by']) && count($data['keyword']['search_fields'])) {
                $sqlLike = '(tbl.id < -1';
                $sqlContentLike = '(lgc.id < -1';
                foreach ($data['keyword']['search_fields'] as $col) {
                    if (is_array($col) && isset($col['content']) && $col['content']) {
                        $sqlContentLike .= ' OR (lgc.' . $col['key'] . ' LIKE :keyword)';
                        continue;
                    }
                    $sqlLike .= ' OR (tbl.' . $col . ' LIKE :keyword)';
                }
                if ($this->hasContent) {
                    $sqlLike .= ') OR ' . $sqlContentLike . ')';
                    if ($sqlLike != '(tbl.id < -1) OR (lgc.id < -1)') {
                        $query = $query->andWhere($sqlLike);
                    }
                } else {
                    $sqlLike .= ')';
                    if ($sqlLike != '(tbl.id < -1)') {
                        $query = $query->andWhere($sqlLike);
                    }
                }

                $query = $query->setParameter('keyword', $data['keyword']['search_by']);
            }
        }
        if (isset($data['is_set_display_order']) && count($data['is_set_display_order'])) {
            $query = $query->andWhere('tbl.displayOrder ' . ($data['is_set_display_order']['type'] == DisplayOrderHelper::$_TYPE_DOWN ? '<' : '>') . ' :is_set_display_order')->setParameter(':is_set_display_order', $data['is_set_display_order']['order']);
        }
        if (isset($data['is_published']) && intval($data['is_published'])) {
            // $now = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $query = $query->andWhere('tbl.isPublished = 1');
                // ->andWhere('(tbl.publishedFromAt IS NULL OR tbl.publishedFromAt >= :now_0)')
                // ->andWhere('(tbl.publishedToAt IS NULL OR tbl.publishedToAt <= :now_24)')
                // ->setParameter('now_0', $now->format(DateTimeHelper::$DATE_FORMAT) . ' ' . DateTimeHelper::$TIME_HOUR_0)
                // ->setParameter('now_24', $now->format(DateTimeHelper::$DATE_FORMAT) . ' ' . DateTimeHelper::$TIME_HOUR_24);
        }
        if (isset($data['is_published_admin']) && intval($data['is_published_admin']) >= 0) {
            $query = $query->andWhere('tbl.isPublished = :is_published_admin')->setParameter(':is_published_admin', $data['is_published_admin']);
        }
        if (isset($data['is_deleted']) && intval($data['is_deleted'])) {
            $query = $query->andWhere('tbl.isDeleted = 0');
        }
        if (isset($data['display_orders']) && count($data['display_orders'])) {
            foreach ($data['display_orders'] as $col => $orderType) {
                $query = $query->addOrderBy('tbl.' . $col, $orderType);
            }
        } else {
            $query = $query->addOrderBy('tbl.displayOrder', 'DESC')
                ->addOrderBy('tbl.createdAt', 'DESC')
                ->addOrderBy('tbl.id', 'DESC');
        }

        if (isset($data['pagination']) && count($data['pagination'])) {
            if (!isset($data['pagination']['page']) || intval($data['pagination']['page']) <= 0) {
                $data['pagination']['page'] = 1;
            }
            if (!isset($data['pagination']['limit']) || intval($data['pagination']['limit']) <= 0) {
                $data['pagination']['limit'] = getenv('PAGINATOR_LIMIT_DEFAULT') !== null ? intval(getenv('PAGINATOR_LIMIT_DEFAULT')) : 20;
            }
            $query = $query->setFirstResult((intval($data['pagination']['page']) - 1) * intval($data['pagination']['limit']))->setMaxResults(intval($data['pagination']['limit']));
        }

        $paginator = new Paginator($query, true);
        $returnedData = [];
        foreach ($paginator as $item) {
            $returnedData[] = $item;
        }
        if ((isset($data['pagination']) && count($data['pagination']))) {
            return [
                'total' => $paginator->count(),
                'total_page' => ceil($paginator->count() / intval($data['pagination']['limit'])),
                'page' => intval($data['pagination']['page']),
                'limit' => intval($data['pagination']['limit']),
                'items' => $returnedData
            ];
        }
        if (isset($data['is_count']) && intval($data['is_count'])) {
            return $paginator->count();
        }
        return $returnedData;
    }

    public function isExisting($id, $data): bool
    {
        $query = $this->createQueryBuilder('tbl')
            ->where('tbl.id != :id')
            ->setParameter(':id', $id);
        if (count($data)) {
            foreach ($data as $key => $val) {
                $query = $query->andWhere('tbl.' . $key . '=:' . $key)->setParameter(':' . $key, $val);
            }
        }
        $query = $query->setFirstResult(1)->setMaxResults(1);
        $paginator = new Paginator($query, true);
        return $paginator->count() > 0;
    }

    public function isExistingLang($id, $data): bool
    {
        $query = $this->createQueryBuilder('tbl')
            ->leftJoin('tbl.langContents', 'lg')
            ->where('tbl.id != :id')
            ->setParameter(':id', $id);
        if (count($data)) {
            foreach ($data as $key => $val) {
                $query = $query->andWhere('lg.' . $key . '=:' . $key)->setParameter(':' . $key, $val);
            }
        }
        $query = $query->setFirstResult(1)->setMaxResults(1);
        $paginator = new Paginator($query, true);
        return $paginator->count() > 0;
    }
}
