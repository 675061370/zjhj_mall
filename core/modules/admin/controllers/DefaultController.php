<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 13:43
 */

namespace app\modules\admin\controllers;


use app\models\Admin;
use app\modules\admin\behaviors\LoginBehavior;
use yii\helpers\VarDumper;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
                'ignore' => ['admin'],
            ],
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

//    public function actionAdmin()
//    {
//        $admin = new Admin();
//        $admin->username = 'admin';
//        $admin->password = \Yii::$app->security->generatePasswordHash('123456');
//        $admin->auth_key = \Yii::$app->security->generateRandomString();
//        $admin->access_token = \Yii::$app->security->generateRandomString();
//        $res = $admin->save();
//        var_dump($res);
//    }
}