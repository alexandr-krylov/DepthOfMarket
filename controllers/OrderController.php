<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Order;
use app\enums\Status;
use app\enums\Type;
use app\enums\Side;
use app\services\OrderResolver;

class OrderController extends Controller
{
    private OrderResolver $resolver;

    public function actionIndex()
    {
        return Order::find()->all();
    }

    public function actionView($id)
    {
        return Order::findOne($id);
    }

    public function actionCreate()
    {
        $order = new Order();
        $order->attributes = $this->request->post();
        $order->status = Status::Active;
        $order->side =
            match ($order->side) {'buy' => Side::Buy, 'sell' => Side::Sell,};
        $order->type = 
            match ($order->type) {'market' => Type::Market, 'limit' => Type::Limit,};
        $order->save();
        $this->resolver = new OrderResolver();
        $result = $this->resolver->resolve($order);
        return $result;
    }

    public function actionViewmy($my_id)
    {
        return Order::find()->where(['owner_id' => $my_id])->all();
    }

    public function actionCancel($id)
    {
        $order = Order::findOne($id);
        $order->status = Status::Canseled;
        $result = $order->save();
        return $result;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}
