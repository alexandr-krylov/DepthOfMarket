<?php

namespace app\controllers;

use app\models\Market;
use yii\rest\Controller;

class VolumeController extends Controller
{
    public function actionView()
    {
        $market = new Market(['ticker' => $this->request->get('ticker')]);
        return [
            'askVolume' => $market->askVolume,
            'bidVolume' => $market->bidVolume,
            'askQuantity' => $market->askQuantity,
            'bidQuantity' => $market->bidQuantity,
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}
