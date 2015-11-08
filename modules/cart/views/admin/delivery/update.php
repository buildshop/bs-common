<?php

if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
if ($this->isAjax) echo Html::closeTag('div');
?>
