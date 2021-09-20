<?php

namespace HK\CoreBundle\Repository;

use HK\CoreBundle\Master\MasterRepository;
use Doctrine\ORM\QueryBuilder;

class NewsRepository extends MasterRepository
{

    protected $hasContent = true;

    public function customQuery($data, QueryBuilder $query): QueryBuilder
    {
        if (isset($data['cate_id']) && intval($data['cate_id']) > 0) {
            $query->andWhere('tbl.cateId = :cateId')->setParameter(':cateId', $data['cate_id']);
        }
        if (!empty($data['show_on'])) {
            $query->andWhere('tbl.showOn = :showOn')->setParameter(':showOn', $data['show_on']);
        }
        if (!empty($data['show_ons'])) {
            $query->andWhere('tbl.showOn IN (:showOn)')->setParameter(':showOn', $data['show_ons']);
        }
        if (isset($data['is_viewmore'])) {
            if ($data['is_viewmore']) {
                $query->andWhere("tbl.viewmoreUrl <> '' AND tbl.viewmoreUrl IS NOT NULL");
            } else {
                $query->andWhere("tbl.viewmoreUrl = '' OR tbl.viewmoreUrl IS NULL");
            }
        }
        return parent::customQuery($data, $query);
    }
}
