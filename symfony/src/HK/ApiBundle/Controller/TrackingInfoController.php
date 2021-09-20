<?php

namespace HK\ApiBundle\Controller;

use HK\CoreBundle\Entity\TrackingInfo;
use HK\CoreBundle\Master\MasterApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TrackingInfoController extends MasterApiController
{
    public function isExisting(Request $req, $deviceId): Response
    {
        $filter = ['deviceId' => $deviceId];
        $trackings = $this->getDoctrine()->getRepository(TrackingInfo::class)->getData($filter);
        return $this->okJson(['isExisting' => count($trackings) > 0]);
    }
    public function add(Request $req): Response
    {

        $data = json_decode($req->getContent(), true);
        $errors = [];
        // if (empty($data['deviceId'])) {
        //     $errors['deviceId'] = 'Device Id is required';
        // }
        if (empty($data['gender'])) {
            $errors['gender'] = 'Gender is required';
        }
        if (empty($data['platform'])) {
            $errors['platform'] = 'Platform is required';
        }
        if (count($errors)) {
            return $this->errorJson('Please input all data required', $errors);
        }
        $tracking = new TrackingInfo();
        // $tracking->setDeviceId($data['deviceId']);
        $tracking->setGender($data['gender']);
        $tracking->setPlatform($data['platform']);
        $this->getDoctrine()->getManager()->persist($tracking);
        $this->getDoctrine()->getManager()->flush();
        return $this->okJson(['isAdded' => true]);
    }
}
