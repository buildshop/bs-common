<style type="text/css">
    /*  div.userData input[type=text] {
          width: 385px;
      }
      div.userData textarea {
          width: 385px;
      }
      #orderedProducts {
          padding: 0 0 5px 0;
      }
      .ui-dialog .ui-dialog-content {
          padding: 0;
      }
      #dialog-modal .grid-view {
          padding: 0;
      }
      #orderSummaryTable tr td {
          padding: 3px;
      }*/
</style>


<?php


if ($model->isNewRecord)
    $action = 'create';
else
    $action = 'update';
echo Html::form($this->createUrl($action, array('id' => $model->id)), 'post', array('id' => 'orderUpdateForm', 'class' => 'form-horizontal'));

if ($model->hasErrors())
    echo Html::errorSummary($model);
?>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'status_id',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeDropDownList($model, 'status_id', Html::listData($statuses, 'id', 'name'), array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'delivery_id',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeDropDownList($model, 'delivery_id', Html::listData($deliveryMethods, 'id', 'name'), array('class' => 'form-control', 'onChange' => 'recountOrderTotalPrice(this)')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'payment_id',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeDropDownList($model, 'payment_id', Html::listData($paymentMethods, 'id', 'name'), array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'paid',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeCheckBox($model, 'paid'); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'discount',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'discount', array('class' => 'form-control')); ?><div class="hint"><?php echo Yii::t('CartModule.admin', 'Применить скидку для общей суммы заказа'); ?></div></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'user_name', array('required' => true,'class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'user_name', array('class' => 'form-control')); ?><?php if ($model->user_id) { ?>
            <div class="hint">
                <?php
                echo CHtml::link(Yii::t('CartModule.admin', 'Редактировать пользователя'), array(
                    '/users/admin/default/update',
                    'id' => $model->user_id,
                ));
                ?>
            </div>
        <?php } ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'user_email', array('required' => true,'class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'user_email', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'user_phone',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'user_phone', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'user_address',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'user_address', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'user_comment',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextArea($model, 'user_comment', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'admin_comment',array('class'=>'control-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextArea($model, 'admin_comment', array('class' => 'form-control')); ?><div class="hint"><?php echo Yii::t('CartModule.admin', 'Этот текст не виден для пользователя.'); ?></div></div>
</div>
<div class="form-group text-center">
    <?= Html::submitButton(($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
</div>


<?php
echo Html::endForm();


?>
