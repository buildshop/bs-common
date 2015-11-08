
<div class="text-center">
    <img src="<?php echo Yii::app()->user->avatarPath; ?>" alt="<?php echo Yii::t('core', 'CHECKUSER', 0) ?>">
    <br/>
    <?php
    echo Yii::t('default', 'HELLO', array(
        '{username}' => Yii::t('core', 'CHECKUSER', 0))
    )
    ?>
</div>
<br/>

<?php
echo Html::form('', 'post', array('id' => 'userblock-login-form', 'class' => 'form'));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($model));
?>
<div class="input-group input-group">
    <span class="input-group-addon">
        <span class="icon-user"></span>
    </span>
    <?= Html::activeTextField($model, 'login', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('login')
    )); ?>
</div>
<br/>
<div class="input-group input-group">
    <span class="input-group-addon">
        <span class="icon-key"></span>
    </span>
    <?= Html::activePasswordField($model, 'password', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('password')
    )); ?>
</div>
<br/>
<div class="input-group">
    <?php echo Html::activeCheckBox($model, 'rememberMe', array('class' => '')); ?>
    <?php echo Html::activeLabel($model, 'rememberMe'); ?>
</div>
<br/>
<div class="text-center">
    <?php
    echo Html::submitButton(Yii::t('UsersModule.default', 'BTN_LOGIN'), array('class' => 'btn btn-success'))
    ?>
</div>


<ul class="list-unstyled">
    <li><?php echo Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), '/users/remind'); ?></li>
    <li><?php echo Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), '/users/register'); ?></li>
</ul>

<?php echo Html::endForm(); ?>