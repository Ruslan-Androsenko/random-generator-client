<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\web\Controller;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;
use app\models\IpAddresses;
use app\models\MacAddresses;

class MacController extends Controller
{
    private function getIpList()
    {
        $curl = new Curl();

        $response = $curl->get(Yii::$app->params['apiMacUrl'] . '/ip/list/');
        $responseData = Json::decode($response);
        $countRecords = count($responseData['ipAddresses']);
        $ipAddresses = [];

        for ($i = 0; $i < $countRecords; $i++) {
            $ipAddresses[] = new IpAddresses();
        }

        IpAddresses::loadMultiple($ipAddresses, $responseData['ipAddresses'], '');

        return $ipAddresses;
    }

    private function getIndexesIpById() {
        $ipAddresses = $this->getIpList();
        $indexesById = [];

        foreach ($ipAddresses as $ipAddress) {
            $indexesById[$ipAddress->id] = $ipAddress->name;
        }

        return $indexesById;
    }

    private function getMacList()
    {
        $curl = new Curl();

        $response = $curl->get(Yii::$app->params['apiMacUrl'] . '/mac/list/');
        $responseData = Json::decode($response);

        $ipAddresses = $this->getIndexesIpById();
        $macAddresses = [];

        foreach ($responseData['macAddresses'] as $item) {
            $macAddress = new MacAddresses();
            $macAddress->attributes = $item;
            $macAddress->ip = $ipAddresses[$macAddress->ip_address_id];

            $macAddresses[] = $macAddress;
        }

        return $macAddresses;
    }

    public function actionList()
    {
        $macAddresses = $this->getMacList();

        return $this->render('list', ['macAddresses' => $macAddresses]);
    }

    public function actionSwitch()
    {
        $id = Yii::$app->request->post('id') ?? 0;
        $curl = new Curl();
        $response = $curl->setPostParams(['id' => $id])->post(Yii::$app->params['apiMacUrl'] . '/mac/changeStatus/');

        return $response;
    }

    public function actionGenerate()
    {
        $ipAddress = new IpAddresses();

        if (Yii::$app->request->isAjax && $ipAddress->load(Yii::$app->request->post()) && $ipAddress->validate()) {
            $curl = new Curl();
            $response = $curl->setPostParams(['ip' => $ipAddress->name])->post(Yii::$app->params['apiMacUrl'] . '/mac/generate/');

            return $response;
        }

        return $this->render('generate', ['model' => $ipAddress]);
    }
}
