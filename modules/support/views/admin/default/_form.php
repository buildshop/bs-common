


<h3>Отправить сообщение</h3>

<?php $form = $this->beginWidget('CActiveForm'); ?>

<?php echo $form->errorSummary($modelForm); ?>

<div class="form-group">
    <?php echo $form->labelEx($modelForm, 'text'); ?>
    <?php echo $form->textArea($modelForm, 'text', array('class' => 'form-control')) ?>
</div>
<div class="text-center">
<?php echo Html::submitButton(Yii::t('app', 'SEND'), array('class' => 'btn btn-success')); ?>
    </div>
<?php $this->endWidget(); ?>



