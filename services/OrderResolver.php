<?php

namespace app\services;

use app\models\Order;
use app\enums\Side;
use app\enums\Type;
use app\enums\Status;
use yii\db\Query;

class OrderResolver
{
    /*
    *   if $order->side = sell : find 
    *
    */
    public function resolve(Order $order)
    {
        // if ($order->type == Type::Limit)
        // {
        //     if ($order->side == Side::Sell)
        //     {
        //         $querySide = Side::Buy->value;
        //         $queryPriceSort = SORT_DESC;
        //     }
        //     var_dump('LIMIT!!!');
        //     die;
        //     return $this->resolveLimitOrder($order);
        // }
        // SELL BY MARKET
        if ($order->type == Type::Market)
        {
            $order->price = 0;
        }
        if ($order->side == Side::Sell)
        {
            $querySide = Side::Buy->value;
            $queryPriceSort = SORT_DESC;
        }
        if ($order->side == Side::Buy)
        {
            $querySide = Side::Sell->value;
            $queryPriceSort = SORT_ASC;
        }
        $query = (new Query())
        ->select('*')
        ->from('orders')
        ->where(['side' => $querySide, 'ticker' => $order->ticker])
        ->andWhere('status IN(' . Status::Active->value . ', ' .  Status::PartialFilled->value . ')')
        ->orderBy(['price' => $queryPriceSort, 'created_at' => SORT_ASC]);
        //price = q1 * price1 + q2 * price2 + ... + qn * pricen / sum(q); if not one price.
        // LOOP THROUGH BUY ORDERS FROM EXPENSIVE TO CHEAP AND FROM OLDER TO NEWER
        $result = [];
        if (empty($query->all())) {
            // CASE NO FOUND BUY ORDERS. MY ORDER WILL REFUSE. DONE
            $order->status = Status::Refused;
            $order->save();
            return $result;
        }
        foreach ($query->all() as $DOMorder) {
            if (($DOMorder['quantity'] - $DOMorder['filled']) >= ($order->quantity - $order->filled))
            {
                // IN NEW ORDER QUANTITY <= THAN IN DOM-ORDER
                $quantity = $order->quantity - $order->filled;
                $price = $this->price($order->filled ?? 0, $order->price ?? 0, $order->quantity - $order->filled, $DOMorder['price']);
                $order1 = (new Order())->findOne($DOMorder['id']);
                $order1->filled = $DOMorder['filled'] + $order->quantity - $order->filled;
                if ($order1->filled == $order1->quantity) {
                    $order1->status = Status::Filled;
                } else {
                    $order1->status = Status::PartialFilled;
                }
                $order1->save();
                $order->filled = $order->quantity;
                $order->price = $price;
                $order->status = Status::Filled;
                $order->save();
                $result[] = ['owner_id' => $order1->owner_id, 'price' => $order1->price, 'quantity' => $quantity];
                return $result;
            }
            else
            {
                //IN NEW ORDER QUANTITY IS BIGGER THAN NEXT ORDER
                $price  = $this->price($order->filled ?? 0, $order->price ?? 0, $DOMorder['quantity'] - $DOMorder['filled'], $DOMorder['price']);
                $quantity = $DOMorder['quantity'] - $DOMorder['filled'];
                $order->price = $price;
                $order->filled = $order->filled + $DOMorder['quantity'] - $DOMorder['filled'];
                $order->status = Status::PartialFilled;
                $order->save();
                $order1 = (new Order())->findOne($DOMorder['id']);
                $order1->filled = $order1->quantity;
                $order1->status = Status::Filled;
                $order1->save();
                $result[] = ['owner_id' => $order1->owner_id, 'price' => $order1->price, 'quantity' => $quantity];
            }
        }
        //LOOP ENDED SO ORDER IS PARTIAL FILLED
        return $result;
    }

    private function price(int $myQty, $myPrice, int $addQty, $addPrice)
    {
        return ($myQty * $myPrice + $addQty * $addPrice) / ($myQty + $addQty);
    }
    private function resolveLimitOrder(Order $order)
    {

    }
}