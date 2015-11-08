

    <div class="whead">

    <h6>Список</h6>
    <div class="clear"></div>
</div>


<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'poll-form',
  //'enableAjaxValidation'=>TRUE,
)); ?>









  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  
              <div class="formRow">
                <div class="grid4"><?php echo $form->labelEx($model, 'title'); ?></div>
                <div class="grid8"><?php echo $form->textField($model, 'title'); ?><?php echo $form->error($model, 'title'); ?></div>
                <div class="clear"></div>
            </div>
  
  

              <div class="formRow">
                <div class="grid4"><?php echo $form->labelEx($model, 'description'); ?></div>
                <div class="grid8"><?php echo $form->textArea($model, 'description'); ?><?php echo $form->error($model, 'description'); ?></div>
                <div class="clear"></div>
            </div>
              <div class="formRow">
                <div class="grid4"><?php echo $form->labelEx($model, 'switch'); ?></div>
                <div class="grid8"><?php echo $form->dropDownList($model,'switch',$model->statusLabels()); ?><?php echo $form->error($model, 'status'); ?></div>
                <div class="clear"></div>
            </div>
  
  
  
  
  

  
  
  
  
  
  
  
  
  
    <div class="formRow noBorderB">
        <div class="grid12 textC"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('admin', 'Add', 0) : Yii::t('admin', 'Save'), array('class' => 'buttonS bGreen')); ?></div>
        <div class="clear"></div>
    </div>




<?php $this->endWidget(); ?>





