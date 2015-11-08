<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
if(!$model->isNewRecord) Yii::app()->tpl->alert('warning', 'Будьте внимательны при редактирование курса. У всех товаров связаны с этим курсом изменится цена!');

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
if ($this->isAjax) echo Html::closeTag('div');
?>
