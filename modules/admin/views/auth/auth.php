





<div class="container">    
    <div id="loginbox" style="margin-top:100px;" class="mainbox col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-default" >
            <div class="panel-heading">
                <div class="panel-title text-center">Войти в админ-панель</div>
            </div>     
            <div style="padding-top:30px" class="panel-body">


                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
                    'enableClientValidation' => false,
                    'clientOptions' => array(
                        'validateOnType' => false,
                        'validateOnSubmit' => false,
                        'validateOnChange' => false,
                        'errorCssClass' => 'has-error',
                        'successCssClass' => 'has-success',
                    ),
                 //   'htmlOptions' => array('class' => 'form-horizontal')
                        ));
                ?>


                <div class="form-group">
                    <?= $form->textField($model, 'login', array('placeholder' => Yii::t('app', 'LOGIN'), 'class' => 'form-control')); ?>  
                    <?= $form->error($model, 'login'); ?>
                </div>
                <div class="form-group">
                    <?= $form->passwordField($model, 'password', array('placeholder' => Yii::t('app', 'PASSWORD'), 'class' => 'form-control')); ?>
                    <?= $form->error($model, 'password'); ?>
                </div>
                <div class="input-group">
                    <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'check')); ?>
                    <?= Html::label(Yii::t('app', 'REMEMBER_ME'), Html::activeId($model, 'rememberMe')) ?>
                </div>
                <div style="margin-top:10px" class="form-group">
                    <div class="col-sm-12 controls text-center">
                        <?= Html::submitButton(Yii::t('app', 'ENTER'), array('class' => 'btn btn-success')); ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>  
            </div>                       
        </div>  
    </div>

</div>
<div class="text-center copyright">{copyright}</div>