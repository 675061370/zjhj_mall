<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%topic_type}}". 
 * 
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $is_delete
 */ 
class TopicType extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%topic_type}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['name', 'sort', 'is_delete'], 'required'],
            [['sort', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'name' => '名称',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
        ]; 
    } 
} 