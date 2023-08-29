<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Order;

class MarketdataController extends Controller
{
    public function actionView()
    {
        $order = Order::find($this->request->get())->one();
        return (object)['ask' => $order->ask, 'bid' => $order->bid];
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}
