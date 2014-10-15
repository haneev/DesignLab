<?php
namespace app\models;

class Snippet extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'snippets';
    }
    
    public function getEngine()
    {
        return $this->hasOne(Engine::className(), ['id' => 'engine_id']);
    }
    public function getQuery()
    {
        return $this->hasOne(Query::className(), ['id' => 'query_id']);
    }
}