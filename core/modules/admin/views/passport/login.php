<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 14:08
 */
?>
<style>
    body {
        background-image: url("<?=Yii::$app->request->baseUrl?>/statics/admin/images/bg-1.gif");
    }

    form {
        max-width: 15rem;
        margin: 5rem auto 1.5rem;
    }

    form.card {
        box-shadow: 0 0 0 .35rem rgba(0, 0, 0, .15);
        border: none;
        background-color: rgba(255, 255, 255, .85);
    }

    form .card-header {
        box-shadow: 0 0 .25rem rgba(0, 0, 0, .2);
        text-shadow: 1px 1px #fff;
        background-color: rgba(0, 0, 0, .1);
    }

    .copyright {
        color: rgba(255, 255, 255, 0.5);
        text-shadow: -1px -1px rgba(0, 0, 0, 0.5);
    }

</style>
<form method="post" class="auto-submit-form card" return="<?= Yii::$app->request->get('return_url') ?>">
    <div class="card-header text-center">
        <b>登录管理后台</b>
    </div>
    <div class="card-body">
        <input class="form-control form-control-sm mb-3" name="username" placeholder="用户名">
        <input class="form-control form-control-sm mb-3" name="password" placeholder="密码" type="password">
        <button class="btn btn-block btn-sm btn-primary submit-btn">登录</button>
    </div>
</form>
<div class="text-center copyright">&copy;禾匠科技2017</div>