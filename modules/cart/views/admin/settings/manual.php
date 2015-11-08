<?php
Yii::app()->tpl->alert('info','Данный список переменных действует также и на <b>"Все заголовки писем"</b>',false);
?>
<?php

foreach($this->module->tpl_keys as $code){ ?>
<div class="formRow little-padd-row">
    <div class="grid3 textR"><code><?php echo $code ?></code></div>
    <div class="grid9"><?php echo Yii::t('CartModule.manual',$code) ?></div>
    <div class="clear"></div>
</div>
<?php } ?>
