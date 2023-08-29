<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\enums\Side;
use app\enums\Type;
use app\enums\Status;

class Order extends ActiveRecord
{
    private ?float $_bid = null;
    private ?float $_ask = null;

    public static function tableName()
    {
        return '{{orders}}';
    }

    public function rules()
    {
        return [
            [['owner_id', 'ticker', 'side', 'type', 'quantity'], 'required'],
            ['price', 'number'],
            [['id', 'status'], 'safe'],
        ];
    }

    public function setBid($bid)
    {
        $this->_bid = (float)$bid;
    }
    public function getBid()
    {
        if ($this->_bid === null)
        {
            $query = Order::find();
            $query->select(['MAX(`price`) AS `bid`']);
            $query->where([
                'ticker' => $this->ticker,
                'side' => Side::Buy->value,
                'type' => Type::Limit->value,
                'status' => [Status::Active->value, Status::PartialFilled->value]
            ]);
            $this->setBid($query->one()['bid']);
        }
        return $this->_bid;
    }
    public function setAsk($ask)
    {
        $this->_ask = (float)$ask;
    }
    public function getAsk()
    {
        if ($this->_ask === null)
        {
            $query = Order::find();
            $query->select(['MIN(`price`) AS `ask`']);
            $query->where([
                'ticker' => $this->ticker,
                'side' => Side::Sell->value,
                'type' => Type::Limit->value,
                'status' => [Status::Active->value, Status::PartialFilled->value]
            ]);
            $this->setAsk($query->one()['ask']);
        }
        return $this->_ask;
    }

}
