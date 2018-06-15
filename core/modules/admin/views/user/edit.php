<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 12:04
 */
/* @var \yii\web\View $this */
$this->title = '编辑用户信息';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/user/index']);
?>

<form method="post" return="<?= $return_url ?>" class="auto-submit-form card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label required">用户名</label>
            <div class="col-sm-4">
                <?php if ($model->isNewRecord): ?>
                    <input class="form-control form-control-sm"
                           value="<?= $model->username ?>" name="username">
                <?php else: ?>
                    <input type="text" readonly class="form-control-plaintext form-control-sm"
                           value="<?= $model->username ?>">
                <?php endif; ?>
            </div>
        </div>

        <?php if ($model->isNewRecord): ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label required">登录密码</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control form-control-sm" value="" name="password">
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">备注</label>
            <div class="col-sm-4">
                <input class="form-control form-control-sm" value="<?= $model->remark ?>" name="remark">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label required">小程序数量</label>
            <div class="col-sm-4">
                <input class="form-control form-control-sm" type="number" step="1" min="0"
                       value="<?= $model->app_max_count ?>" name="app_max_count">
                <div class="fs-sm text-muted">此用户可以创建的小程序的数量，填写0则表示不限制用户创建小程序的数量</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label required">权限设置</label>
            <div class="col-sm-4" style="padding-top: calc(.5rem - 1px * 2);">
                <?php $admin_permission_list = json_decode($model->permission, true);
                if (!is_array($admin_permission_list)) $admin_permission_list = []; ?>
                <?php foreach ($permission_list as $item): ?>
                    <label class="mr-5">
                        <input type="checkbox" <?= in_array($item->name, $admin_permission_list) ? 'checked' : null ?>
                               value="<?= $item->name ?>" name="permission[]">
                        <?= $item->display_name ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2"></label>
            <div class="col-sm-4 offset-sm-2">
                <a class="btn btn-primary btn-sm submit-btn" href="javascript:">保存</a>
            </div>
        </div>
    </div>
</form>