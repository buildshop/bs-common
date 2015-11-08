
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

<?php //echo $form->errorSummary($model); ?>

<?php
if($model->hasErrors()){
    echo $model->getError('choise_id');
}

echo $fn->renderField('PortletPollVote_choice_id', $choices);


echo Html::link('Голосовать', 'javascript:poll(' . $model->id . ')', array('class' => 'btn btn-info'));
?>


<?php $this->endWidget(); ?>


