<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\AboutPage;
use HK\AdminBundle\FormType\AboutPageType;
use HK\CoreBundle\Translation\Translation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use HK\CoreBundle\Configuration\Configuration;
use HK\AdminBundle\Router\Router;

class AdminTranslatorController extends MasterController
{

    protected $entityClass = AboutPage::class;

    protected $entityTypeClass = AboutPageType::class;

    protected $icon = 'settings';

    public function indexTranslation(Request $req, ParameterBagInterface $param): Response
    {
        $translators = Translation::instance()->getTranslators();
        $this->dataRender['trans'] = $translators;
        $data = [];
        $languages = Configuration::instance()->getAllLanguages();
        $this->dataRender['langs'] = [];
        foreach ($languages as $lang) {
            $data[$lang] = [];
            $dataLang = Translation::instance()->getCurrentTranslate($lang, $param);
            foreach ($translators as $key => $val) {
                if (empty(trim($key))) {
                    continue;
                }
                if (empty($val)) {
                    $this->dataRender[$key] = $key;
                }
                $data[$lang][$key] = isset($dataLang[$key]) ? $dataLang[$key] : $key;
            }
            $this->dataRender['langs'][] = [
                'lang' => $lang,
                'language' => $this->trans('lbl.language.' . $lang)
            ];
        }

        $this->dataRender['translates'] = $data;
        $this->dataRender['current'] = Configuration::instance()->getCurrentLang();
        if ($req->isMethod('GET')) {
            return $this->render('admin/' . $this->controllerText . '/index.html.twig', [
                'breadcrumbs' => [
                    [
                        'class' => '',
                        'name' => $this->controllerText,
                        'url' => $this->generateUrl(Router::$_PREFIX . $this->controllerText),
                        'active' => ''
                    ]
                ],
                'data' => $this->dataRender
            ]);
        }
        $data = $req->request->all();
        foreach ($languages as $lang) {
            Translation::instance()->saveCurrentTranslator($lang, $data[$lang], $param);
        }
        return $this->okJson(['message' => $this->trans('lbl.translator-success'), 'isReload' => '1']);
    }
}
