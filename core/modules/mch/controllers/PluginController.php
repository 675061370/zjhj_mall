<?php

namespace app\modules\mch\controllers;

use app\hejiang\CloudPlugin;
use yii\helpers\VarDumper;

class PluginController extends Controller
{
    public function actionIndex()
    {
        $data = CloudPlugin::getList();
        return $this->render('index', [
            'list' => $data['list'],
            'topic' => $data['topic'],
        ]);
    }

    public function actionBuy()
    {
        $plugin_id = \Yii::$app->request->post('plugin_id');
        $this->renderJson(CloudPlugin::buy($plugin_id));
    }

    public function actionInstall()
    {
        $plugin_id = \Yii::$app->request->get('plugin_id');
        $this->renderJson(CloudPlugin::install($plugin_id));
    }

    public function actionPay()
    {
        $this->renderJson(CloudPlugin::pay(\Yii::$app->request->get('order_no')));
    }
}