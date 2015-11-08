<?php


Yii::app()->tpl->openWidget(array(
    'title' => 'ЗАГАЛОВОК',
    'htmlOptions' => array('id' => 'grid-update', 'class' => 'check')
));

$this->renderPartial('_admin', array('model' => $model));
Yii::app()->tpl->closeWidget();
?>
