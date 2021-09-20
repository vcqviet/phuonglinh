<?php
namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class AboutPageRepository extends MasterRepository
{

    protected $hasContent = true;

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        return parent::customQuery($data, $query);
    }

    public function bkGetByNameKey($namekey)
    {
        return $this->findOneBy([
            'nameKey' => $namekey,
            'isDeleted' => '0'
        ]);
    }

    public function getByNameKey($nameKey)
    {
        $data = $this->findOneBy([
            'nameKey' => $nameKey,
            'isDeleted' => 0,
            'isPublished' => 1
        ]);
        if ($data != null) {
            return $data->getContent();
        }
        return '';
    }
}