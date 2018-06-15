<?php

defined('YII_RUN') or exit('Access Denied');
?>
<div class="admin-default-index">
    <h1>后台首页，欢迎页</h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
</div>
