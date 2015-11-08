    <div class="fluid">
    <div class="widget grid8">
        
<div class="whead">
    <h6><?= Yii::t('DeliveryModule.default','CREATE_DELIVERY');?></h6><div class="clear"></div>
</div>

        
        <div id="response-box">
<?php echo $this->renderPartial('form', array('users' => $users,'delivery'=>$delivery, 'model' => $model, 'mails' => $mails)); ?>
</div>



</div>
     <div class="widget grid4">
         <div class="whead">
    <h6><?= Yii::t('DeliveryModule.default','DELIVERY_RESULT');?></h6>
    
    <div class="progress contentProgress hidden">
<div class="bar barG" style="width:0;"></div>
</div>
    <div class="clear"></div>
</div>
         <div class="formRow">
             <div id="progress-send"></div>
         <div id="sended-result"></div>
         </div>
     </div>
    
    
</div>