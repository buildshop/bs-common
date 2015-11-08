<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns'=>false,
    'columns' => array(
        array('class' => 'CCheckBoxColumn'),
        array(
            'name' => 'name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textL'),
            'value' => '$data->name',
        ),
        array(
            'name' => 'alias_wgt',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textL'),
            'value' => '$data->alias_wgt',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
if(Yii::app()->request->isAjaxRequest) echo Html::closeTag('div');

?>
