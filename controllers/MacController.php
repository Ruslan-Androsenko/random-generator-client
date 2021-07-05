<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\web\Controller;
use linslin\yii2\curl\Curl;
use app\models\IpAddresses;
use app\models\MacAddresses;

class MacController extends Controller
{
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
