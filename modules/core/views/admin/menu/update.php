<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('id' => 'grid-update', 'class' => 'check fluid')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>
