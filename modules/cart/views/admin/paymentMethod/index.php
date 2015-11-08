<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => true,
    'name' => $this->pageName,
    'filter' => $model,
));
if ($this->isAjax) echo Html::closeTag('div');
?>
