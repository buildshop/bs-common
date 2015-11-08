<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name'=>$this->pageName,
    'headerOptions'=>false,
    'autoColumns'=>false,
    'columns' => array(
        array('class' => 'CCheckBoxColumn'),
        array('class' => 'HandleColumn'),
        'id',
        'name',
        array(
            'name' => 'content',
            'value' => '($data->content)?"---":$data->widget;',
        ),
        array(
            'name' => 'position',
            'value' => '$data->showPosition("$data->position")',
        ),
        array(
            'name' => 'access',
            'value' => 'Yii::app()->access->getName($data->access)', 
        ),
        array(
            'name' => 'expire',
            'value' => '($data->expire>0)?CMS::purchased_time("$data->expire"):"Без ограничений"',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
if(Yii::app()->request->isAjaxRequest) echo Html::closeTag('div');