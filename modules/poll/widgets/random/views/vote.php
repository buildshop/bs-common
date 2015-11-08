<div id="poll_container">

    <div class="text-center"><h4><?php echo $model->title ?></h4></div>


    <?php
    $fn = new CPollHelper($model);


    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'portlet-poll-form',
        'action' => '/poll/' . $model->id,
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
            ));
    ?>


    <?php
    echo Html::hiddenField('widget', 1);
    echo $form->error($userVote, 'choice_id');
    echo $fn->renderField('PortletPollVote_choice_id', $choices);
    ?>

    <?php ///$template = '<div class="checkbox">{input}{label}</div>'; ?>
    <?php
    /* echo $form->radioButtonList($userVote, 'choice_id', $choices, array(
      'template' => $template,
      'separator' => '',
      'name' => 'PortletPollVote_choice_id')); */
    ?>
    <?php echo $form->error($userVote, 'choice_id'); ?>

    <div class="text-center">
        <?php
        echo Html::link('Голосовать', 'javascript:poll(' . $model->id . ')', array('class' => 'btn btn-info'));
        ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    function poll(id){
        app.ajax('/poll/'+id, $("#poll_container form").serialize(), function(data){
            $("#poll_container").html(data)
        });
    }
</script>