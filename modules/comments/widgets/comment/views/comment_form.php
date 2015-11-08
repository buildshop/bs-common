


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-create-form',
    'action' => array('/comments/create'), //$currentUrl
    'enableClientValidation' => true,
    'enableAjaxValidation' => true, // Включаем аякс отправку
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
    ),
    'htmlOptions' => array('class' => 'form', 'name' => 'test')
        ));
?>

<style>


    #comment-form{
        margin: 0 auto;
        width:560px;
    }
    #comment-form h3{
        margin-bottom: 20px;
    }
    #comment-form .comment-form-author{
        width: 100px;
    }
    #comment-form .comment-form-author-image{
        margin-top: 20px;
    }
    #comment-form .comment-form-text{
        width:460px;

    }
    #comment-form .comment-form-text textarea{
        width:370px;
    }
    #comment-form .comment-form-text .comment-form-buttons{
        margin-top: 10px;
    }
    .input-row{
        margin: 0 0 15px 0;
    }
    .input-row input{
        margin-top: 5px;
    }
</style>


<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert:true
    };
</script>

<?php echo Html::hiddenField('object_id', $object_id); ?>
<?php echo Html::hiddenField('owner_title', $owner_title); ?>
<?php echo Html::hiddenField('model', $model); ?>



<div class="input-group">
    <span class="input-group-addon"><?php echo Html::image(Yii::app()->user->avatarPath); ?></span>
    <?php echo $form->textArea($comment, 'text', array('rows' => 4, 'class' => 'form-control')); ?>
</div>
<div class="text-right" style="margin-top:15px;">
    <?php
    //echo Html::button(Yii::t('default', 'SEND'), array('class' => 'btn btn-success', 'onclick' => 'fn.comment.add("#comment-create-form");'));
    echo Html::ajaxSubmitButton(Yii::t('app', 'SEND'), array('/comments/create'), array(//$currentUrl
        'type' => 'post',
        'data' => 'js:$("#comment-create-form").serialize()',
        'dataType' => 'json',
        'success' => 'js:function(data) {

if(data.success){

                    var ft = ' . time() . '+comment.foodTime;
                    $.session.set("caf",ft);
                  $.fn.yiiListView.update("comment-list");
                  $.jGrowl(data.message);
}else{

}
                     
                    }',
        'error' => 'js:function(jqXHR, textStatus, errorThrown ){
       console.log(jqXHR);
                }'
            ), array('class' => 'btn btn-success'));
    ?>
</div>


<?php $this->endWidget(); ?>

