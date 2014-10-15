<?php
namespace app\models;

class Query extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'queries';
    }
}