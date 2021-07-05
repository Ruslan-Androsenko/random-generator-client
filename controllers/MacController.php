<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\web\Controller;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
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

    private function getMacListByIpIds($ipIds)
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

            if (in_array($macAddress->ip_address_id, $ipIds)) {
                $macAddresses[] = $macAddress;
            }
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

    public function actionExport()
    {
        return $this->render('export');
    }

    public function actionExportAll()
    {
        $macAddresses = $this->getMacList();
        $fileName = Yii::getAlias('@docs') . '/export_all_mac_addresses.csv';

        $this->saveToCsv($macAddresses, $fileName);

        return Yii::$app->response->sendFile($fileName);
    }

    public function actionExportSubnet()
    {
        $subnet = Yii::$app->request->post('subnet') ?? '';
        $curl = new Curl();

        $response = $curl->setGetParams(['subnet' => $subnet])->get(Yii::$app->params['apiMacUrl'] . '/ip/getBySubnet/');
        $responseData = Json::decode($response);

        $ipIds = ArrayHelper::getColumn($responseData, 'id');
        $macAddresses = $this->getMacListByIpIds($ipIds);
        $fileName = Yii::getAlias('@docs') . '/export_all_mac_addresses_by_subnet.csv';

        $this->saveToCsv($macAddresses, $fileName);

        return Yii::$app->response->sendFile($fileName);
    }

    private function saveToCsv($macAddresses, $fileName)
    {
        try {
            $header = '\xEF\xBB\xBF ID; Mac; Status; IP; \n';

            // Записываем заголовок в начало файла
            file_put_contents($fileName, $header);

            $exportRows = '';
            $startLine = 0;

            foreach ($macAddresses as $macAddress) {
                $status = $macAddress->status ? 'Активен': 'Не активен';
                $exportRows .= $macAddress->id . '; ' . $macAddress->name . '; ' . $status . '; ' . $macAddress->ip . ' \n';

                if($startLine++ % 50 == 0){
                    // Сохраняем каждые 50 строк
                    file_put_contents($fileName, $exportRows, FILE_APPEND);
                    $exportRows = '';
                }
            }

            // Сохраняем то что осталось
            file_put_contents($fileName, $exportRows, FILE_APPEND);
        } catch (\Exception $ex) {
            echo "{$ex->getMessage()}\n";
        }
    }
}
