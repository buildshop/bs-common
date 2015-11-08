<div class="text-center">
    <img src="<?php echo Yii::app()->user->avatarUrl(); ?>" alt="<?php echo Yii::app()->user->login ?>">
    <br/>
    <?php
    echo Yii::t('default', 'HELLO', array(
        '{username}' => Html::link(Yii::app()->user->login, '/users/profile'))
    )
    ?>
</div>
<br/>
<ul class="list-unstyled">
    <?php if (Yii::app()->user->isSuperuser) { ?>
        <li><?php echo Html::link(Yii::t('default', 'ADMIN_PANEL'), '/admin'); ?></li>
    <?php } ?>
    <li><?php echo Html::link(Yii::t('UsersModule.default', 'PROFILE'), '/users/profile'); ?></li>
    <li><?php echo Html::link(Yii::t('default', 'LOGOUT'), '/users/logout'); ?></li>
</ul>
<ul class="list-unstyled">
    <li><?php echo Yii::t('core', 'CHECKUSERS', 0) ?>: <?php echo $online['totals']['guest'] ?></li>
    <li><?php echo Yii::t('core', 'CHECKUSERS', 3) ?>: <?php echo $online['totals']['bot'] ?></li>
    <li><?php echo Yii::t('core', 'CHECKUSERS', 1) ?>: <?php echo $online['totals']['users'] ?></li>
    <li><?php echo Yii::t('core', 'CHECKUSERS', 2) ?>: <?php echo $online['totals']['admin'] ?></li>
    <li><?php echo Yii::t('core', 'TOTAL') ?>: <?php echo $online['totals']['all'] ?></li>
</ul>

