<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class SettingWebsiteRepository extends MasterRepository
{

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        if(isset($data['cate_id'])) {
            $query = $query->andWhere('tbl.cateId = :cateId')->setParameter(':cateId', $data['cate_id']);
        }
        if (isset($data['name_key'])) {
            $query = $query->andWhere('tbl.nameKey = :nameKey')->setParameter(':nameKey', $data['name_key']);
        }
        return parent::customQuery($data, $query);
    }

    public function updateValue($key, $value)
    {
        $entity = $this->getByNameKey($key);
        if ($entity == null) {
            return false;
        }
        $entity->setValue($value);
        return $this->saveEntity($entity);
    }

    public function getByNameKey($key)
    {
        $data = $this->getData([
            'name_key' => $key
        ]);
        if (count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    public function getValue($key)
    {
        $entity = $this->getByNameKey($key);
        if ($entity == null) {
            return '';
        }
        return $entity->getValue();
    }
}