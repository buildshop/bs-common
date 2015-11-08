<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
/*if ($model->isService) {
    echo CHtml::openTag('div', array('class' => 'borderB'));
    Yii::app()->tpl->alert('info', Yii::t('UsersModule.site', 'USER_IS_SERVICE', array('{SERVICE}' => $model->service)));
    echo CHtml::closeTag('div');
}*/
echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>
