<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '安装插件';
$this->params['active_nav_group'] = 10;
?>
<style>
    .plugin-list {
        margin-left: -.75rem;
    }

    .plugin-item {
        display: inline-block;
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: .15rem;
        background: #fff;
        margin-left: .75rem;
        margin-bottom: .75rem;
    }

    .plugin-item .plugin-pic {
        background-size: cover;
        background-position: center;
        width: 10rem;
        height: 10rem;
        background-color: #f6f6f6;
    }

    .plugin-item .plugin-name {
        text-align: center;
        margin: .5rem 0;
        font-weight: bold;
    }

    .plugin-item .plugin-detail {
        margin: .5rem 0;
        padding: 0 .5rem;
    }

    .plugin-item .plugin-price {
        color: #ff4544;
        font-weight: bold;
    }

</style>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store/index']) ?>">我的商城</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3">
    <?php if (is_array($topic) && count($topic) > 0): ?>
        <?php foreach ($topic as $item): ?>
            <?php if (time() >= $item['show_time']['begin'] && time() <= $item['show_time']['end']): ?>
                <div class="alert alert-info">
                    <div><?= $item['title'] ?></div>
                    <div><?= $item['content'] ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!$list || !is_array($list) || count($list) == 0): ?>
        <div class="text-muted text-center p-5">暂无任何插件</div>
    <?php else: ?>
        <div class="plugin-list">
            <?php foreach ($list as $item): ?>
                <div class="plugin-item">
                    <div class="plugin-pic" style="background-image: url(<?= $item['pic_url'] ?>)"></div>
                    <div class="plugin-name"><?= $item['display_name'] ?></div>
                    <?php if ($item['is_open'] == 0): ?>
                        <div class="text-muted plugin-detail text-center">未开放</div>
                    <?php else: ?>
                        <?php if ($item['order_id']): ?>
                            <div class="plugin-detail" flex="dir:left box:last">
                                <div class="plugin-price">￥<?= $item['price'] ?></div>
                                <div>
                                    <?php if ($item['is_pay']): ?>
                                        <?php if ($item['is_install'] == 1): ?>
                                            <span class="badge badge-success">已安装</>
                                        <?php else: ?>
                                            <a href="javascript:" class="badge badge-primary install-plugin"
                                               data-id="<?= $item['id'] ?>">安装插件</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?= $urlManager->createUrl(['mch/plugin/pay', 'order_no' => $item['order_no']]) ?>"
                                           class="badge badge-primary pay-plugin"
                                           data-id="<?= $item['id'] ?>">待付款</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="plugin-detail" flex="dir:left box:last">
                                <div class="plugin-price">￥<?= $item['price'] ?></div>
                                <div>

                                    <?php if ($item['version_ok'] == 1): ?>
                                        <a href="javascript:" class="badge badge-primary buy-plugin"
                                           data-id="<?= $item['id'] ?>">立即购买</a>
                                    <?php else: ?>
                                        <a class="badge badge-default" href="javascript:" data-container="body"
                                           data-toggle="popover"
                                           data-placement="top"
                                           data-trigger="focus"
                                           data-content="此插件要求商城版本v<?= $item['version'] ?>以上，当前商城版本v<?= $item['current_version'] ?>">版本不符</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    $(document).on("click", ".buy-plugin", function () {
        var id = $(this).attr("data-id");
        var url = "<?=$urlManager->createUrl(['mch/plugin/buy'])?>";
        $.myConfirm({
            content: "确认购买插件？",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                        plugin_id: id,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                location.reload();
                            }
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", ".pay-plugin", function () {
        var href = $(this).attr("href");
        $.myLoading({});
        $.ajax({
            url: href,
            dataType: "json",
            success: function (res) {
                $.myLoadingHide();
                $.myAlert({
                    content: res.msg,
                });
            }
        });
        return false;
    });


    $(document).on("click", ".install-plugin", function () {
        var id = $(this).attr("data-id");
        var url = "<?=$urlManager->createUrl(['mch/plugin/install'])?>";
        $.myConfirm({
            content: "确认安装插件？",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                        plugin_id: id,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                location.reload();
                            }
                        });
                    },
                });
            }
        });
    });
</script>