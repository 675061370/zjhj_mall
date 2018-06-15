<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 19:32
 */

namespace app\modules\mch\models;

use app\models\TopicType;

/**
 * @property Topic $model
 */
class TopicTypeEditForm extends Model
{
    public $model;

    public $name;
    public $sort;
  //public $is_delete;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'default', 'value' => 1000],
            [['name', 'sort'], 'required'],
            ['sort', 'integer'],
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
            'store_id' => 'Store ID',
            'sort' => '排序：升序',
            'name' => '名称'
        ];
    }

    public function save()
    {
        if (!$this->validate())
           return $this->getModelError();
        $this->model->is_delete = 0;
        $this->model->attributes = $this->attributes;
        if ($this->model->save())
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        else
            return $this->getModelError($this->model);
    }
}