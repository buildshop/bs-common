<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();

Yii::app()->tpl->openWidget(array(
    'title' => Yii::t('app', 'DB_LIST')
));
$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $data_db,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'filename',
            'header' => 'Название файла',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'textL'),
        ),
        array(
            'name' => 'url',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textC'),
        ),
    )
        )
);
Yii::app()->tpl->closeWidget();

if(Yii::app()->request->isAjaxRequest){
    echo Html::closeTag('div');
}







