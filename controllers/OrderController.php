<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Order;
use app\enums\Status;
use app\enums\Type;
use app\enums\Side;
use app\services\OrderResolver;
use Exception;

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

    public function actionViewmy()
    {
        return Order::find()
        ->select(['*', 'ROUND(price, 2) AS price'])
        ->where(['owner_id' => $this->request->get('owner_id')])
        ->all();
    }

    public function actionCancel()
    {
        $order = Order::findOne($this->request->post('id'));
        if (
            (
                $order->status == Status::Active->value
             or $order->status == Status::PartialFilled->value
            )
             and $order->type == Type::Limit->value
        )
        {
            $order->status = Status::Canceled->value;
            $order->save();
            return $order;
        }
        throw new Exception('Order couldn\'t be canceled');
    }

    public function actionRedemption()
    {   if (null === $this->request->post('ticker'))
        {
            return false;
        }
        $orders = Order::findAll(['ticker' => $this->request->post('ticker')]);
        foreach ($orders as $order)
        {
            $order->status = Status::Redempted->value;
            $order->save();
        }
        return true;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}
