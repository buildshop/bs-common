
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
            ));
    ?>

            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model->seo, 'title'); ?></div>
                <div class="grid9"><?php echo $form->textField($model->seo, 'title'); ?><?php echo $form->error($model->seo, 'title'); ?></div>
                <div class="clear"></div>
            </div>





            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'title_ru'); ?></div>
                <div class="grid9"><?php echo $form->textField($model, 'title_ru'); ?><?php echo $form->error($model, 'title_ru'); ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'hometext_ru'); ?></div>
                <div class="grid9"><?php echo $form->textArea($model, 'hometext_ru', array('class' => 'editor')); ?><?php echo $form->error($model, 'hometext_ru'); ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'bodytext_ru'); ?></div>
                <div class="grid9"><?php echo $form->textArea($model, 'bodytext_ru', array('class' => 'editor')); ?><?php echo $form->error($model, 'bodytext_ru' ); ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'seo_title_ru'); ?></div>
                <div class="grid9"><?php echo $form->textField($model, 'seo_title_ru'); ?><?php echo $form->error($model, 'seo_title_ru'); ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'seo_keywords_ru'); ?></div>
                <div class="grid9"><?php echo $form->textField($model, 'seo_keywords_ru'); ?><?php echo $form->error($model, 'seo_keywords_ru'); ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'seo_description_ru'); ?></div>
                <div class="grid9"><?php echo $form->textField($model, 'seo_description_ru'); ?><?php echo $form->error($model, 'seo_description_ru'); ?></div>
                <div class="clear"></div>
            </div>





            <div class="formRow">
                <div class="grid3"><?php echo $form->labelEx($model, 'seo_alias'); ?></div>
                <div class="grid9"><?php echo $form->textField($model, 'seo_alias',array('id'=>'alias')); ?><span id="alias_result"></span><?php echo $form->error($model, 'seo_alias'); ?></div>
                <div class="clear"></div>
            </div>
    <div class="formRow noBorderB">
        <div class="grid12 textC"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('admin', 'Add', 0) : Yii::t('admin', 'Save'), array('class' => 'buttonS bGreen')); ?></div>
        <div class="clear"></div>
    </div>

<?php $this->endWidget(); ?>




<script type="text/javascript">
    $(function(){

        init_translitter('News');
        var language = $.cookie('language')==null ? 'ru' :$.cookie('language');
        if(language=='ru'){
            var tab_option = { active: 0 };
        } else if(language=='en') {
            var tab_option = { active: 1 };
        }else{
            var tab_option = { active: 2 };
        }
        
        $( "#tabs-language" ).tabs(tab_option);
    });
</script>