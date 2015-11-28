<?php
Yii::app()->tpl->alert('info','Данный список переменных действует также и на <b>"Все заголовки писем"</b>',false);
?>
<?php

foreach($this->module->tpl_keys as $code){ ?>
<div class="form-group little-padd-row">
    <div class="col-sm-4"><code><?php echo $code ?></code></div>
    <div class="col-sm-8"><?php echo Yii::t('CartModule.manual',$code) ?></div>
</div>
<?php } ?>
