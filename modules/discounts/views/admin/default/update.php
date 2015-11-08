<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
echo $form->tabs();
Yii::app()->tpl->closeWidget();
?>
