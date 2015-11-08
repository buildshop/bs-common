<div class="row">
    <div class="col-xs-7">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $this->pageName; ?></h3>
            </div>
            <div class="panel-body">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'user-register-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array('class' => 'form')
                        ));
                ?>

                <?php echo $form->errorSummary(array($user)); ?>

                <div class="input-group">
                    <?= $form->textField($user, 'login', array('class' => 'form-control', 'placeholder' => $user->getAttributeLabel('email'))); ?>
                </div>
                <br/>
                <div class="input-group">
                    <?= $form->textField($user, 'username', array('class' => 'form-control', 'placeholder' => $user->getAttributeLabel('username'))); ?>
                </div>
                <br/>
                <div class="input-group">
                    <?= $form->passwordField($user, 'password', array('class' => 'form-control', 'placeholder' => $user->getAttributeLabel('password'))); ?>
                </div>
                <br/>
                <div class="input-group">
                    <?= $form->passwordField($user, 'confirm_password', array('class' => 'form-control', 'placeholder' => $user->getAttributeLabel('confirm_password'))); ?>
                </div>



                <?php if (CCaptcha::checkRequirements()): ?>

                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" style="padding:0;">
                            <?php
                            $this->widget('CCaptcha', array(
                                'clickableImage' => false,
                                'showRefreshButton' => true,
                                'buttonLabel' => '',
                                'buttonOptions' => array(
                                    'class' => 'refresh_captcha icon-loop-2'
                                )
                            ));
                            ?></span>
                        <?= $form->textField($user, 'verifyCode', array('class' => 'form-control', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
                    </div>

                <?php endif; ?>

                <div class="text-center">
                    <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn btn-lg btn-success')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('UsersModule.default', 'AUTH'); ?></h3>
            </div>
            <div class="panel-body">
                <?php
                Yii::import('mod.users.forms.UserLoginForm');
                $this->renderPartial('login', array('model' => new UserLoginForm()));
                ?>
            </div>
        </div>
    </div>
</div>



<div class="row buttons">
    <?= Html::link(Yii::t('UsersModule.default', 'LOGIN'), array('login/login')) ?><br>
    <?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), array('/users/remind')) ?>
</div>
