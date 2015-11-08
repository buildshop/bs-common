<?php
echo Html::form($this->createUrl('/users/login'), 'post', array('id' => 'user-login-form', 'class'=>'form'));
echo Html::errorSummary($model);
?>
<div class="input-group">
    <?= Html::activeTextField($model, 'login', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('login'))); ?>
</div>
<br/>
<div class="input-group">
    <?= Html::activePasswordField($model, 'password', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('password'))); ?>
</div>
<div class="input-group">
    <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'form-control')); ?>
</div>
<div class="text-center">
    <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_LOGIN'),array('class'=>'btn btn-lg btn-default')); ?>
</div>

<div class="row buttons">
<?php echo Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), array('register/register'),array('onClick'=>'getWindow("register/register"); return false;')) ?><br>
<?php echo Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), array('/users/remind'),array('onClick'=>'getWindow("users/remind"); return false;')) ?>
</div>
    <?php echo Html::endForm(); ?>