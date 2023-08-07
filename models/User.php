<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public function rules()
    {
        return [
            ['user_name', 'string'],
        ];
    }

    public static function tableName()
    {
        return '{{users}}';
    }
}
