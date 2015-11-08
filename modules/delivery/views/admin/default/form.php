<script>

    function send(formid, reload){
        var str = $(formid).serialize();
        $.ajax({
            url: $(formid).attr('action'),
            type: 'POST',
            data: str,
            success: function(data){
                $(reload).html(data);
            },
            complete: function(){

            } 
        });
            
            
        
    }

</script>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'id' => 'div-form',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
    'htmlOptions' => array('action' => '/delivery/admin', 'name' => 'div-form')
        ));
?> 
<?php

$countUsers = count($users);
$countDelivery = count($delivery);
$countAll = ($countUsers + $countDelivery);
?>
<div class="formRow">
    <div class="grid4"><?php echo $form->labelEx($model, 'themename'); ?></div>
    <div class="grid8"><?php echo $form->textField($model, 'themename'); ?><?php echo $form->error($model, 'themename'); ?></div>
    <div class="clear"></div>
</div>
<div class="formRow">
    <div class="grid4"><?php echo $form->labelEx($model, 'text'); ?></div>
    <div class="grid8"><?php echo $form->textArea($model, 'text'); ?><?php echo $form->error($model, 'text'); ?></div>
    <div class="clear"></div>
</div>
<div class="formRow">
    <div class="grid4"><?php echo $form->labelEx($model, 'from'); ?></div>
    <div class="grid6 noSearch"><?php echo $form->dropDownList($model, 'from', array('all' => 'Всем (' . $countAll . ')', 'users' => 'Пользователям (' . $countUsers . ')', 'delivery' => 'Подписчикам (' . $countDelivery . ')'),array('class'=>'select')); ?><?php echo $form->error($model, 'from'); ?></div>
    <div class="clear"></div>
</div>
<div class="formRow fluid">
    <div class="textC"><a href="javascript:void(0)" class="buttonS bGreen" onclick="send('#div-form','#response-box')">Подготовить к отправки</a></div>
    <div class="clear"></div>
</div>


<?php $this->endWidget(); ?>


