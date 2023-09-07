<?php

namespace app\models;

use yii\base\Model;
use yii\db\Query;
use app\enums\Side;
use app\enums\Status;

class Market extends Model
{
    private ?float $_bidVolume = null;
    private ?float $_askVolume = null;
    private ?int $_bidQuantity = null;
    private ?int $_askQuantity = null;
    public $ticker;

    public function getBidVolume()
    {
        if ($this->_bidVolume === null)
        {
            $this->_getBid();
        }
        return $this->_bidVolume;
    }
    public function setBidVolume($bidVolume)
    {
        $this->_bidVolume = (float)$bidVolume;
    }
    public function getAskVolume()
    {
        if ($this->_askVolume === null)
        {
            $this->_getAsk();
        }
        return $this->_askVolume;
    }
    public function setAskVolume($askVolume)
    {
        $this->_askVolume = (float)$askVolume;
    }
    public function getBidQuantity()
    {
        if ($this->_bidQuantity === null)
        {
            $this->_getBid();
        }
        return $this->_bidQuantity;
    }
    public function setBidQuantity($bidQuantity)
    {
        $this->_bidQuantity = (int)$bidQuantity;
    }
    public function getAskQuantity()
    {
        if ($this->_askQuantity === null)
        {
            $this->_getAsk();
        }
        return $this->_askQuantity;
    }
    public function setAskQuantity($askQuantity)
    {
        $this->_askQuantity = (int)$askQuantity;
    }
    private function _getBid()
    {
        $query = (new Query())  
        ->select(['SUM(price * (quantity - filled)) AS bid_volume', 'SUM(quantity - filled) AS bid_quantity'])
        ->from('orders')
        ->where(['ticker' => $this->ticker])            
        ->andWhere(['side' => Side::Buy->value])
        ->andWhere(['status' => [Status::Active->value, Status::PartialFilled->value]]);
        $result = $query->one();
        $this->setBidVolume($result['bid_volume']);
        $this->setBidQuantity($result['bid_quantity']);
    }
    private function _getAsk()
    {
        $query = (new Query())
        ->select(['SUM(price * (quantity - filled)) AS ask_volume', 'SUM(quantity - filled) AS ask_quantity'])
        ->from('orders')
        ->where(['ticker' => $this->ticker])            
        ->andWhere(['side' => Side::Sell->value])
        ->andWhere(['status' => [Status::Active->value, Status::PartialFilled->value]]);
        $result = $query->one();
        $this->setAskVolume($result['ask_volume']);
        $this->setAskQuantity($result['ask_quantity']);
    }
}
