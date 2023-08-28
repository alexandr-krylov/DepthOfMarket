<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Order;

class MarketdataController extends Controller
{
    public function actionView()
    {
        return Order::find($this->request->get())->one()->bid;
        // var_dump($this->request->get());

    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}
