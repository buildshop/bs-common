<p><?= Yii::t('CallbackWidget.default', 'CALLBACK_TEXT'); ?></p>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'callback-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => '',
        'onsubmit' => "return false;", /* Disable normal form submit */
        'onkeypress' => " if(event.keyCode == 13){ callbackSend(); } " /* Do ajax call when user presses enter key */
    ),
        ));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', $form->error($model, 'phone'));

if ($sended)
    Yii::app()->tpl->alert('success', Yii::t('CallbackWidget.default', 'CALLBACK_SUCCESS'));
?>

<div class="form-group">
    <?php echo $form->label($model, 'phone', array('class' => 'sr-only')); ?>
    <?php echo $form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => '(xxx) xxx xx xx')); ?>
</div>
<div class="text-center">
<?php echo Html::Button(Yii::t('CallbackWidget.default', 'CALLBACK_BUTTON_SEND'), array('onclick' => 'callbackSend();', 'class' => 'btn btn-primary wait')); ?>


</div>


<?php $this->endWidget(); ?>
