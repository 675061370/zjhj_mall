<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 10:29
 */

namespace app\modules\mch\controllers;


use app\models\OrderMessage;
use app\modules\mch\models\OrderMessageForm;

class GetDataController extends Controller
{
    /**
     * 获取订单提示列表
     */
    public function actionOrder()
    {
        $form = new OrderMessageForm();
        $form->store_id = $this->store->id;
        $form->limit = 5;
        $arr = $form->search();
        $this->renderJson([
            'code' => 0,
            'msg' => '',
            'data' => $arr['list']
        ]);
    }

    /**
     * 删除订单提示
     */
    public function actionMessageDel($id = null)
    {
        OrderMessage::updateAll(['is_read' => 1], ['id' => $id]);
    }
    //删除声音提示
    public function actionSound()
    {
        $id = \Yii::$app->request->get('id');
        OrderMessage::updateAll(['is_sound' => 1], ['id'=>$id]);
    }
}