
<?php
$contact = Yii::app()->settings->get('contacts');
?>
<div class="row">
    <div class="col-md-12 col-sm-6">
        <div class="text-center">
            <h3><?= Yii::t('ContactsModule.default', 'CONTACT_INFO') ?></h3>
        </div>
        <?php
        $this->widget('mod.contacts.widgets.map.MapStaticWidget', array('pk' => 1));
        ?>
        <hr/>
        <?php if ($contact['phone']) { ?>
            <div><?= Yii::t('ContactsModule.ConfigContactForm', 'PHONE') ?>: <?= $contact['phone'] ?></div>
        <?php } ?>
        <?php if ($contact['skype']) { ?>
            <div><?= Yii::t('ContactsModule.ConfigContactForm', 'SKYPE') ?>: <?= $contact['skype'] ?></div>
        <?php } ?>
        <?php if ($contact['address']) { ?>
            <div><?= Yii::t('ContactsModule.ConfigContactForm', 'ADDRESS') ?>: <?= $contact['address'] ?></div>
        <?php } ?>
        <hr/>
    </div>

    <div class="col-md-12  col-sm-12">

        <div class="text-center">
            <h4><?= Yii::t('ContactsModule.default', 'FB_FORM_NAME') ?></h4>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'contact_form',
            'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
            ),
            'htmlOptions' => array('name' => 'contact_form', 'class' => '')
        ));

        if ($model->hasErrors())
            Yii::app()->tpl->alert('danger', Html::errorSummary($model));

        if (Yii::app()->user->hasFlash('success')) {
            Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
        }
        

        ?>
        <div class="form-group">
            <?= $form->labelEx($model, 'name'); ?>
            <?= $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('name'))); ?>
            <?= $form->error($model, 'name'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'phone'); ?>
            <?= $form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('phone'))); ?>
            <?= $form->error($model, 'phone'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'email'); ?>
            <?= $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
            <?= $form->error($model, 'email'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'msg'); ?>
            <?= $form->textArea($model, 'msg', array('class' => 'form-control', 'rows' => '5', 'placeholder' => $model->getAttributeLabel('msg'))); ?>
            <?= $form->error($model, 'msg'); ?>
        </div>




        <?php if (Yii::app()->settings->get('contacts', 'enable_captcha')) { ?>
            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->labelEx($model, 'verifyCode', array('class' => '')) ?>
                </div>
                <div class="col-sm-4">
                    <?php
                    $this->widget('CCaptcha', array(
                        'imageOptions' => array('class' => 'captcha'),
                        'clickableImage' => true,
                        'showRefreshButton' => false,
                    ))
                    ?>

                </div>
                <div class="col-sm-5">   
                    <?= $form->textField($model, 'verifyCode', array('class' => 'form-control')) ?>
                    <?= $form->error($model, 'verifyCode', array(), false, false) ?>
                </div>
            </div>
        <?php } ?>

        <div class="text-center">
            <?= Html::submitButton(Yii::t('app', 'SEND_MSG'), array('class' => 'btn btn-default')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>



</div>

