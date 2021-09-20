<?php
namespace HK\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Entity\AboutPage;
use HK\CoreBundle\Entity\SettingWebsite;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Master\MasterFrontEndController;
use HK\CoreBundle\Entity\District;
use HK\CoreBundle\Entity\Ward;

class CommonController extends MasterFrontEndController
{

    public function seoOption(Request $req): Response
    {
        $repo = $this->getDoctrine()->getRepository(SettingWebsite::class);
        $data = [];
        $data['page_title'] = $repo->getValue(SettingWebsite::$_KEY_SEO_DEFAULT_TITLE);
        $data['description'] = $repo->getValue(SettingWebsite::$_KEY_SEO_DEFAULT_DESCRIPTION);
        $data['keywords'] = $repo->getValue(SettingWebsite::$_KEY_SEO_DEFAULT_KEYWORDS);
        $data['page_url'] = $repo->getValue(SettingWebsite::$_KEY_GENERAL_MAIN_DOMAIN);
        $data['image_url'] = '';
        $data['site_title'] = $repo->getValue(SettingWebsite::$_KEY_SEO_DEFAULT_TITLE);
        $data['homepage_url'] = $repo->getValue(SettingWebsite::$_KEY_GENERAL_MAIN_DOMAIN);
        $data['default_lang'] = $repo->getValue(SettingWebsite::$_KEY_LOCATION_DEFAULT_LANGUAGE) == 'en' ? 'en_US' : 'vi_VN';
        $data['second_lang'] = $data['default_lang'] == 'vi_VN' ? 'en_US' : 'vi_VN';
        $data['site_name'] = $repo->getValue(SettingWebsite::$_KEY_SEO_DEFAULT_SITENAME);
        $data['favicon'] = $repo->getValue(SettingWebsite::$_KEY_GENERAL_FAVICON);
        $data['custom_header'] = $repo->getValue(SettingWebsite::$_KEY_SEO_CUSTOM_HEADER_JS);
        
        $dataRequest = [
            'pageTitle' => $req->get('pageTitle', null),
            'pageUrl' => $req->get('pageUrl', null),
            'imageUrl' => $req->get('imageUrl', null),
            'description' => $req->get('description', null)
        ];
        if (isset($dataRequest['pageUrl'])) {
            $data['page_url'] = $dataRequest['pageUrl'];
        }
        if (isset($dataRequest['imageUrl'])) {
            $data['image_url'] = $repo->getValue(SettingWebsite::$_KEY_GENERAL_MAIN_DOMAIN) . $dataRequest['imageUrl'];
        }
        if (isset($dataRequest['pageTitle'])) {
            $data['page_title'] = $dataRequest['pageTitle'];
        }
        if (isset($dataRequest['description'])) {
            $data['description'] = $dataRequest['description'];
        }
        return $this->render('app/common/seo-option.html.twig', [
            'data' => $data
        ]);
    }

    public function header(Request $req): Response
    {
        $dataRender = [];
        
        $dataRender['_lang'] = Configuration::instance()->getCurrentLang();
        return $this->render('app/common/header.html.twig', [
            'data' => $dataRender
        ]);
    }

    public function footer(Request $req): Response
    {
        return $this->render('app/common/footer.html.twig', [
            'data' => $this->dataRender
        ]);
    }

    public function language(Request $req): Response
    {
        return $this->render('app/common/language.html.twig', []);
    }
    
    public function getDistrict(Request $req) {
        $city_id = $req->get('city_id', 0);
        $data = $this->getDoctrine()->getRepository(District::class)->getData(['city_id' => $city_id]);
        $rt = [];
        foreach($data as $item) {
            $rt[] = ['id' => $item->getId(), 'name' => $item->getName()];
        }
        return $this->okJson($rt);
    }
    public function getWard(Request $req) {
        $district_id = $req->get('district_id', 0);
        $data = $this->getDoctrine()->getRepository(Ward::class)->getData(['district_id' => $district_id]);
        $rt = [];
        foreach($data as $item) {
            $rt[] = ['id' => $item->getId(), 'name' => $item->getName()];
        }
        return $this->okJson($rt);
    }
}
