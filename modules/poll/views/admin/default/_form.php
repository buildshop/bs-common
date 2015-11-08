
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'poll-form',
        //'enableAjaxValidation'=>TRUE,
        ));
?>

<div class=" grid6">
    <div class="widget">
        <div class="whead">
            <h6>Список</h6>
            <div class="clear"></div>
        </div>

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
            <div class="grid8"><?php echo $form->checkBox($model, 'switch', array('class' => 'check')); ?><?php echo $form->error($model, 'status'); ?></div>
            <div class="clear"></div>
        </div>

        <div class="formRow noBorderB">
            <div class="grid12 textC"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('admin', 'Add', 0) : Yii::t('admin', 'Save'), array('class' => 'buttonS bGreen')); ?></div>
            <div class="clear"></div>
        </div>

    </div>
</div>

<div class=" grid6">

    <?php $currentPoll = PollChoice::model()->findAll(array('condition' => '`t`.`poll_id`=' . $model->id)); ?>

    <div class="widget">

        <div class="whead">

            <h6>Выборки</h6>
            <a href="javascript:void(0)" onClick="addInput('#poll-choice','prepend');"><span class="tableOptions icon-plus"></span></a>
            <div class="clear"></div>
        </div>

        <table class="tDefault tMedia">
            <thead>
                <tr>
                    <th>Название</th>
                    <th class="button-column">Функция</th>
                </tr>
            </thead>
            <tbody id="poll-choice">
                <?php
                if (count($currentPoll)) {
                    foreach ($currentPoll as $row) {
                        ?>
                        <tr id="filter_<?php echo $row->id; ?>">
                            <td class="textL formRow"><input type="text" value="<?php echo $row->name; ?>" name="PollChoice[update][<?php echo $row->id; ?>]" /></td>
                            <td class="tableToolbar"><a class="icon-trashcan bRed tablectrl_xlarge2" title="Удалить" onClick="removeInput('#filter_<?php echo $row->id; ?>', '<?php echo $row->id; ?>');" href="javascript:void(0)" /></td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr  >
                        <td colspan="2" class="textC formRow">

                            <?php echo CHtml::link(Yii::t('admin', 'Add choice'), 'javascript:void(0)', array('onclick' => 'addInput("#poll-choice");', 'class' => 'buttonS bBlue')); ?>

                        </td>

                    </tr>
                <?php } ?>       
            </tbody>
        </table>
    </div>


    <?php if (count($model->choices)) { ?>
        <div class="widget ">

            <div class="whead">
                <h6>Результат голосования: <?php echo $model->title ?></h6>
                <div class="clear"></div>
            </div>
            <?php
            $this->renderPartial('_results', array('model' => $model));
            ?>

        </div>
    <?php } ?>
</div>
<?php $this->endWidget(); ?>







<script type="text/javascript">

    function addInput(container,type){
            
        if(type == 'prepend'){
            $(container).prepend('<tr><td class="textL formRow"><input type="text" name="PollChoice[create][]" /></td><td class="tableToolbar"><a class="icon-trashcan bRed tablectrl_xlarge2 newChoice" onclick="$(this).parent().parent().remove();" href="javascript:void(0)" title="Удалить"></a></td></tr>');
        }else {
            $(container).append('<tr><td class="textL formRow"><input type="text" name="PollChoice[create][]" /></td><td class="tableToolbar"><a class="icon-trashcan bRed tablectrl_xlarge2 newChoice" onclick="$(this).parent().parent().remove();" href="javascript:void(0)" title="Удалить"></a></td></tr>');
        }
       
    }
    
    function removeInput(id, filter_id){
       
        $.ajax({
            type: 'POST',
            url:'/admin/poll/update?id=<?php echo $model->id ?>',
            data:{remove:true,id:filter_id},
            success:function(){
                $(id).remove();
            }
        });
    }



    function dialogGridPoll(dialogContent, t){
        var that = t;
        $("body").append($("<div/>",{
            "id":"dialog"
        }));
        $("#dialog").dialog({
            autoOpen: false,
            resize:false,
            closeText: "",
            title:$(t).attr("title"),
            draggable:false,
            dialogClass: "dialog-alert",
            modal: true,
            closeOnEscape: true,
            open: function(){
                $("#dialog").html(dialogContent);
            },
            close: function(){
                $(this).dialog("close"); 
                alert('dsa');
            },
            buttons: [{
                    text: "ОК",
                    click: function() {
                        alert('dasdsadas');
                        (that).parent().parent().remove();
                        (t).parent().parent().addClass('11111111111111');
                        //$(this).dialog("close");
                        (that).parent().parent().addClass('11111111111111');
                    }
                },{
                    text: "Отмена",
                    click: function() {
                        $(this).dialog("close");            
                                  
                    }
                }]
        }).dialog("open");
    }
    
    
    
    $(function(){
        $('.newChoice').on('click',function(){
            dialogGridPoll('Вы уверены, что хотите удалить данный элемент?', this);
        });
        init_uniform();
    });




</script>





