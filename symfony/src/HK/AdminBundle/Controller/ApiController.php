<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Entity\CmsUser;
use HK\AdminBundle\FormType\CmsUserType;

class ApiController extends MasterController
{

    protected $entityClass = CmsUser::class;

    protected $entityTypeClass = CmsUserType::class;

    protected $isIndexCustom = false;

    protected $isAddEditCustom = false;

    protected $icon = 'activity';

    protected $isDisplayOrder = false;

    protected $isDisplayPublishedColumn = false;

    protected $hasContent = true;

    public function allLanguages(Request $req): Response
    {
        $data = Configuration::instance()->getAllLanguages();
        $default = Configuration::instance()->getCurrentLang();
        $returnData = [];
        foreach ($data as $lang) {
            if($lang == $default) {
                $returnData[] = [
                    'key' => $lang,
                    'is_default' => true,
                    'lang' => $this->trans('lbl.language.'.$lang)
                ];
                continue;
            }
            $returnData[] = [
                'key' => $lang,
                'is_default' => false,
                'lang' => $this->trans('lbl.language.'.$lang)
            ];
        }
        return $this->okJson($returnData);
    }
}
