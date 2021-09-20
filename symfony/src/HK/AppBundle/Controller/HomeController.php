<?php

namespace HK\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\Master\MasterFrontEndController;
use HK\CoreBundle\Configuration\Configuration;
use HK\CoreBundle\Entity\Telesale;
use HK\CoreBundle\Helper\SessionHelper;
use HK\CoreBundle\Entity\Order;
use HK\CoreBundle\Helper\DateTimeHelper;
use HK\CoreBundle\Helper\NumberHelper;
use HK\CoreBundle\Entity\Ward;
use HK\CoreBundle\Entity\District;
use HK\CoreBundle\Entity\City;
use HK\CoreBundle\Entity\PhoneProvider;
use HK\CoreBundle\Entity\Product;

class HomeController extends MasterFrontEndController
{

    public function setLanguage(Request $req): Response
    {
        $lang = $req->get('rblang', '');
        Configuration::instance()->setCurrentLang($lang);
        return $this->okJson([
            'lang' => Configuration::instance()->getCurrentLang()
        ]);
    }

    public function index(Request $req): Response
    {
        return $this->redirect('/admin/login');
        if ($req->isMethod('GET')) {
            return $this->render('app/homepage/index.html.twig', [
                'data' => $this->dataRender
            ]);
        }
        $data = $req->request->all();
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => $data['phonenumber']
            ]);
        if (count($telesale)) {
            SessionHelper::instance()->set(Telesale::$_SESSION, $data['phonenumber']);
            return $this->okJson([]);
        }
        return $this->errorJson('Số điện thoại không tồn tại !');
    }

    public function telesale(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->redirectToRoute('hk.home.login');
        }
        $this->dataRender['today'] = DateTimeHelper::instance()->getDMY(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        $this->dataRender['telesale'] = $telesale[0]->getFullName();
        $this->dataRender['order_total'] = 0;
        $this->dataRender['telesale_phone_number'] = $telesale[0]->getPhoneNumber();

        $arrStatus = Order::getOrderStatusList();
        $this->dataRender['order_statuses'] = [
            [
                'id' => Order::$_ORDER_STATUS_WAITING,
                'name' => $arrStatus[Order::$_ORDER_STATUS_WAITING]
            ]
        ];
        for ($i = 1; $i <= $telesale[0]->getCallTimes(); $i++) {
            $this->dataRender['order_statuses'][] = [
                'id' => Order::$_ORDER_STATUS_CALLING . '_' . $i,
                'name' => $arrStatus[Order::$_ORDER_STATUS_CALLING] . ' lần ' . $i
            ];
        }

        $this->dataRender['order_statuses'][] = [
            'id' => Order::$_ORDER_STATUS_CONFIRMED,
            'name' => $arrStatus[Order::$_ORDER_STATUS_CONFIRMED]
        ];
        $this->dataRender['order_statuses'][] = [
            'id' => Order::$_ORDER_STATUS_CANCEL,
            'name' => $arrStatus[Order::$_ORDER_STATUS_CANCEL]
        ];

        // $this->dataRender['cities'] = [];
        // $cities = $this->getDoctrine()
        //     ->getRepository(City::class)
        //     ->getData();
        // foreach ($cities as $ct) {
        //     $ci = [
        //         'id' => $ct->getId(),
        //         'name' => $ct->getName(),
        //         'districts' => []
        //     ];

        //     foreach ($ct->getDistricts() as $ds) {
        //         $di = [
        //             'id' => $ds->getId(),
        //             'name' => $ds->getName(),
        //             'wards' => []
        //         ];
        //         foreach ($ds->getWards() as $wd) {
        //             $di['wards'][$wd->getId() . ''] = [
        //                 'id' => $wd->getId(),
        //                 'name' => $wd->getName()
        //             ];
        //         }
        //         $ci['districts'][$di['id'] . ''] = $di;
        //     }
        //     $this->dataRender['cities'][$ci['id'] . ''] = $ci;
        // }
        //$this->dataRender['cities'] = json_encode($this->dataRender['cities'], JSON_UNESCAPED_UNICODE);
        //print_r($this->dataRender['cities']);
        //die;
        $this->dataRender['phone_providers'] = [];
        $providers = $this->getDoctrine()
            ->getRepository(PhoneProvider::class)
            ->getData();
        foreach ($providers as $item) {
            $this->dataRender['phone_providers'][] = [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }
        $this->dataRender['product_codes'] = [];
        $productCodes = $this->getDoctrine()
            ->getRepository(Product::class)
            ->getData();
        foreach ($productCodes as $item) {
            $this->dataRender['product_codes'][] = [
                'id' => $item->getId(),
                'name' => $item->getCode(),
                'fullName' => $item->getName()
            ];
        }
        $this->dataRender['order_statuses'] = json_encode($this->dataRender['order_statuses'], true);
        $this->dataRender['phone_providers'] = json_encode($this->dataRender['phone_providers'], true);
        $this->dataRender['product_codes'] = json_encode($this->dataRender['product_codes'], true);
        return $this->render('app/homepage/telesale.html.twig', [
            'data' => $this->dataRender
        ]);
    }

    public function orderCalling(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $productCode = $req->get('product_code', '-1');
        $phoneProvider = $req->get('phone_provider', '-1');
        $status = $req->get('status', '-1');
        if (intval($status) == -1) {
            $status = '';
        }
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->getById($productCode);
        $productCode = '';
        if ($product != null) {
            $productCode = $product->getCode();
        }
        $providers = [];
        $phone = $this->getDoctrine()
            ->getRepository(PhoneProvider::class)
            ->getById($phoneProvider);
        if ($phone != null) {
            foreach ($phone->getPrefixes() as $dt) {
                $providers[] = $dt->getName();
            }
        }
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getData([
                'not_order_statuses' => [
                    Order::$_ORDER_STATUS_CALL_FAILED,
                    Order::$_ORDER_STATUS_DONE
                ],
                'product_code' => $productCode,
                'order_status' => $status,
                'prefix_phone_numbers' => $providers,
                'telesale_phone_number' => $telesale[0]->getPhoneNumber(),
                'from_text' => $req->get('importat_from', ''),
                'to_text' => $req->get('importat_to', ''),
            ]);
        $rt = [];
        $products = [];
        foreach ($this->getDoctrine()
            ->getRepository(Product::class)
            ->getData() as $item) {
            $products[$item->getCode()] = $item->getName();
        }

        foreach ($orders as $order) {
            // $order = new Order();
            $rt[] = $this->getRecord($order, $products);
        }
        return $this->okJson($rt);
    }

    private function getRecord($order, $products)
    {
        return [
            'id' => $order->getId(),
            'product_code' => $order->getProductCode() . ' - ' . $products[$order->getProductCode()],
            'import_at' => DateTimeHelper::instance()->getDMY($order->getImportAt()),
            'full_name' => $order->getFullName(),
            'phone_number' => $order->getPhoneNumber(),
            'phone_provider' => $order->getPhoneProvider(),
            'address' => $order->getAddress(),
            'ward' => $order->getWard(),
            'district' => $order->getDistrict(),
            'city' => $order->getCity(),
            'cost' => NumberHelper::formatPrice($order->getCost()),
            'call_times' => $order->getCallTimes(),
            'order_status' => $order->getOrderStatus(),
            'remark' => $order->getRemark()
        ];
    }

    public function orderUpdateStatus(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateOrderStatus($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateWard(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        $ward = $this->getDoctrine()
            ->getRepository(Ward::class)
            ->getById($data['val']);
        if ($ward == null) {
            return $this->errorJson('Không tìm thấy phường/xã !');
        }
        $data['val'] = $ward->getName();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateWard($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateDistrict(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        $district = $this->getDoctrine()
            ->getRepository(District::class)
            ->getById($data['val']);
        if ($district == null) {
            return $this->errorJson('Không tìm thấy quận/huyện !');
        }
        $data['val'] = $district->getName();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateDistrict($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateCity(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        $city = $this->getDoctrine()
            ->getRepository(City::class)
            ->getById($data['val']);
        if ($city == null) {
            return $this->errorJson('Không tìm thấy tỉnh/thành phố !');
        }
        $data['val'] = $city->getName();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateCity($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateFullName(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateOrderFullName($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateAddress(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateOrderAddress($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }

    public function orderUpdateRemark(Request $req): Response
    {
        $telesale = $this->getDoctrine()
            ->getRepository(Telesale::class)
            ->getData([
                'phone_number' => SessionHelper::instance()->get(Telesale::$_SESSION)
            ]);
        if (count($telesale) == 0) {
            SessionHelper::instance()->set(Telesale::$_SESSION, '');
            return $this->errorJson('Bạn chưa đăng nhập, vui lòng đăng nhập !');
        }
        $data = $req->request->all();
        if ($this->getDoctrine()
            ->getRepository(Order::class)
            ->updateOrderRemark($data)
        ) {
            return $this->okJson([]);
        }
        return $this->errorJson('Chưa cập nhật được, vui lòng thử lại !');
    }
}
