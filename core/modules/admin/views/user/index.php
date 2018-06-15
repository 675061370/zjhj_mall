<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 11:44
 */
/* @var \yii\web\View $this */
/* @var \app\models\Admin[] $list */
$this->title = '用户列表';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/user/index']);
?>
<table class="table table-bordered bg-white">
    <thead>
    <tr>
        <th>ID</th>
        <th>用户</th>
        <th>可创建小程序数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php foreach ($list as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td>
                <div><?= $item->username ?></div>
                <div class="text-muted fs-sm"><?= $item->remark ?></div>
            </td>
            <td><?= $item->app_max_count == 0 ? '无限制' : $item->app_max_count ?></td>
            <td>
                <a href="<?= $url_manager->createUrl(['admin/user/modify-password', 'id' => $item->id]) ?>"
                   class="modify-password">修改密码</a>
                <a href="<?= $url_manager->createUrl(['admin/user/edit', 'id' => $item->id]) ?>">编辑</a>
                <a href="<?= $url_manager->createUrl(['admin/user/delete', 'id' => $item->id]) ?>"
                   class="delete">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]) ?>
<script>
    $(document).on("click", ".modify-password", function () {
        var href = $(this).attr("href");
        $.myPrompt({
            title: "修改密码",
            content: "请输入新密码：",
            confirm: function (val) {
                if (!val) {
                    $.myToast({
                        content: "密码不能为空",
                    });
                    return;
                }
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    type: "post",
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                        password: val,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                        });
                    }
                });

            }
        });
        return false;
    });
    $(document).on("click", ".delete", function () {
        var href = $(this).attr("href");
        $.myConfirm({
            content: "确认删除此用户？此操作将不可恢复！",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    type: "post",
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                        });
                        location.reload();
                    }
                });

            }
        });
        return false;
    });
</script>