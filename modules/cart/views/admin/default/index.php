<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'name' => $this->pageName,
    'enableHeader' => true,
    /*'rowCssStyleExpression' => function($row, $data) {
        if (!empty($data->status_color)) {
            return 'background-color:#' . $data->status_color . '';
        } else {
            return '';
        }
    },*/
));
if ($this->isAjax) echo Html::closeTag('div');
?>
