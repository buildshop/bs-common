

<b>Рассылка</b>


<?php if (Yii::app()->user->hasFlash('success')) { ?>
    <div class="flash-alert-success"><?php echo Yii::app()->user->getFlash('success') ?></div>




<?php } else { ?>


    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => true,
        'id' => 'delivery-form',
        'action' => '/delivery/send',
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
        'htmlOptions' => array('name' => 'delivery-form')
            ));
    ?>




    <div>
        <div style="text-align:left;margin-top:15px;margin-left:20px;width:130px"><?php echo $form->label($model, 'email'); ?></div>
        <?php echo $form->textField($model, 'email'); ?>
    </div>
    <?php if (Yii::app()->user->hasFlash('error')) { ?>
    <div class="flash-alert-error"><?php echo Yii::app()->user->getFlash('error') ?></div>
<?php } ?>
    <a href="javascript:void(0)" class="btn1" onclick="send('#delivery-form','#delivery-responce')">Подписаться</a>


    <?php $this->endWidget(); ?>

<?php } ?>