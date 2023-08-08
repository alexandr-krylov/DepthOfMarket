<?php

namespace app\services;

use app\models\Order;
use app\enums\Side;
use app\enums\Type;
use yii\db\Query;

class OrderResolver
{
    /*
    *   if $order->side = sell : find 
    *
    */
    public function resolve(Order $order)
    {
        if ($order->side == Type::Market)
        {
            if ($order->type == Side::Sell)
            {
                // $query = (new Query())
                // ->select()
            }
            return 'side sell';
        }
        return true;
    }
}