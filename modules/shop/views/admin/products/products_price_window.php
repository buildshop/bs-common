<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateprice-form',
        ));
echo Yii::app()->tpl->alert('warning', 'Внимание товары которые привязаны к валюте и/или используют конфигурации изменены не будут', false);
?>

<div class="formRow noBorderB noBorderT">

    <?=
    $form->textField($model, 'price', array(
        'placeholder' => $model->getAttributeLabel('price')
    ));
    ?>
<?= $form->error($model, 'price'); ?>


</div>
<?php $this->endWidget(); ?>