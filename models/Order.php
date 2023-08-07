<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName()
    {
        return '{{orders}}';
    }

    public function rules()
    {
        return [
            [['owner_id', 'ticker', 'side', 'type', 'quantity'], 'required'],
            ['price', 'number'],
            [['id'], 'safe'],
        ];
    }
}
