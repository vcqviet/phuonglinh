<?php
namespace HK\CoreBundle\Master;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use HK\CoreBundle\Helper\PaginationHelper;
use HK\CoreBundle\Configuration\Configuration;

class MasterApiController extends AbstractController
{

    private $translator;

    protected $logger;

    protected $controller = '';

    protected $action = '';

    protected $controllerText = '';

    protected $form = null;

    protected $dataRender = [];

    protected $dataRequest = [];

    public function __construct(RequestStack $requestStack, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->dataRender['pagination'] = PaginationHelper::instance()->getPageLimit($requestStack->getCurrentRequest());
        $this->dataRender['pagination']['total_page'] = 1;
        $this->dataRequest = $requestStack->getCurrentRequest()->request->all();
        $this->translator = $translator;
        $this->logger = $logger;
        $this->dataRender['_lang'] = Configuration::instance()->getCurrentLang();
    }

    public function trans($idString, $parameters = []): string
    {
        return $this->translator->trans($idString, $parameters, null, null);
    }

    public function outputJson($data): Response
    {
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function okJson($data = []): Response
    {
        return $this->outputJson([
            'status' => 0,
            'data' => $data
        ]);
    }

    public function errorJson($message, $data = []): Response
    {
        return $this->outputJson([
            'status' => 1,
            'data' => $data,
            'error' => $this->trans($message)
        ]);
    }
}
