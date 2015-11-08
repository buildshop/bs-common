
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('class' => 'form-horizontal')
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="form-group">
    <div class="col-sm-4"><?= $form->labelEx($model, 'name', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8"><?= $form->textField($model, 'name', array('class' => 'form-control')) ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= $form->labelEx($model, 'alias', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8"><?= $form->textField($model, 'alias', array('class' => 'form-control')) ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?//= Html::activeLabelEx('UserGroup', 'access', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::checkBoxList('UserGroup[access_action][]', 'access', array('delete'=>'Удаление','create'=>'Создание','update'=>'Редактирование'),array('class' => '')) ?></div>
</div>
<div class="form-group buttons text-center">
<?php echo Html::submitButton(Yii::t('app', 'SEND'), array('class' => 'btn btn-success')); ?>
</div>
<?php
$this->endWidget();
Yii::app()->tpl->closeWidget();
?>

