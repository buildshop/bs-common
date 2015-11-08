<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'enableHeader'=>true,
    'name'=>$this->pageName,
    'filter' => $model,
    'filterCssClass' => 'filters'
));
if ($this->isAjax) echo Html::closeTag('div');
?>

