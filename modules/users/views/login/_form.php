<?php
echo Html::form($this->createUrl('/users/login'), 'post', array('id' => 'user-login-form', 'class' => 'form'));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($model));
?>
<div class="input-group">
    <span class="input-group-addon">
        <span class="fa fa-user"></span>
    </span>
    <?=
    Html::activeTextField($model, 'login', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('login')
    ));
    ?>
</div>
<br/>
<div class="input-group">
    <span class="input-group-addon">
        <span class="fa fa-key"></span>
    </span>
    <?=
    Html::activePasswordField($model, 'password', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('password')
    ));
    ?>
</div>

<br/>
<div class="input-group">
    <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'form-control2')); ?>
    <?= Html::activeLabel($model, 'rememberMe'); ?>
</div>
<ul class="list-unstyled">
    <li><?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), '/users/remind') ?></li>
    <li><?= Html::link(Yii::t('UsersModule.default', 'BTN_REGISTER'), '/users/register') ?></li>
</ul>
<div class="input-group">
    <?= Html::link(Yii::t('UsersModule.default', 'BTN_LOGIN'), 'javascript:login()',array('class'=>'btn btn-success')); ?>
</div>

<?php echo Html::endForm(); ?>

