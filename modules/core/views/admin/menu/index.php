<?php

if (Yii::app()->request->isAjaxRequest) {


//$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');

    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name'=>$this->pageName,
    //  'headerOptions'=>false,
    // 'enableCustomActions' => false,
    'autoColumns' => false,
    'selectableRows' => 0,
    'columns' => array(
        array('class' => 'HandleColumn'),
        array(
            'name' => 'label',
            'type' => 'raw',
            'value' => 'Html::link("$data->label",Html::encode($data->url))',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
if (Yii::app()->request->isAjaxRequest)
    echo Html::closeTag('div');
?>
