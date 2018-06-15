<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/6
 * Time: 10:20
 */

namespace app\modules\admin\controllers;


use app\models\Store;
use app\modules\admin\models\AppEditForm;
use yii\data\Pagination;

class AppController extends Controller
{
    public function actionIndex()
    {
        $query = Store::find()->where([
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id DESC')->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
            'app_max_count' => \Yii::$app->admin->identity->app_max_count,
            'app_count' => Store::find()->where([
                'admin_id' => \Yii::$app->admin->id,
                'is_delete' => 0,
            ])->count(),
        ]);
    }

    public function actionEdit()
    {
        $form = new AppEditForm();
        $form->attributes = \Yii::$app->request->post();
        $form->admin_id = \Yii::$app->admin->id;
        return $this->renderJson($form->save());
    }

    public function actionEntry($id)
    {
        $store = Store::findOne([
            'id' => $id,
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ]);
        if (!$store) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
        }
        \Yii::$app->session->set('store_id', $store->id);
        return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['mch/store/index']))->send();
    }

    public function actionDelete($id)
    {
        $store = Store::findOne([
            'id' => $id,
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ]);
        if ($store) {
            $store->is_delete = 1;
            $store->save();
        }
        return $this->renderJson([
            'code' => 0,
            'msg' => '操作成功',
        ]);
    }
}