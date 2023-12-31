<?php

namespace app\controllers;

use yii\rest\Controller;
use app\enums\Type;
use app\enums\Side;
use app\enums\Status;
use yii\db\Query;

class DomController extends Controller
{
    public function actionView()
    {
        $ticker = $this->request->get('ticker');
        $redOrders = (new Query())
        ->select([
            'ROUND(price, 2) AS price',
            'quantity' => 'SUM(quantity - filled)',
            'volume' => 'ROUND(price * SUM(quantity - filled), 2)',
            'side'
        ])
        ->from('orders')
        ->where([
            'ticker' => $ticker,
            'type' => Type::Limit->value,
            'side' => Side::Sell->value,
            ])
        ->andWhere('status IN(' . Status::Active->value . ', ' . Status::PartialFilled->value . ')')
        ->groupBy('price')
        ->orderBy(['price' => SORT_DESC])->all();

        $greenOrders = (new Query())
        ->select([
            'price' => 'ROUND(price, 2)',
            'quantity' => 'SUM(quantity - filled)',
            'volume' => 'ROUND(price * SUM(quantity - filled), 2)',
            'side'
        ])
        ->from('orders')
        ->where([
            'ticker' => $ticker,
            'type' => Type::Limit->value,
            'side' => Side::Buy->value,
            ])
        ->andWhere('status IN(' . Status::Active->value . ', ' . Status::PartialFilled->value . ')')
        ->groupBy('price')
        ->orderBy(['price' => SORT_DESC])->all();
        $dom = array_merge($redOrders, $greenOrders);
        return $dom;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}