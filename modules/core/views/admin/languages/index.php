<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
        ),
        array(
            'name' => 'default',
            'type' => 'text',
            'filter' => array(
                '1' => Yii::t('app', 'YES'),
                '0' => Yii::t('app', 'NO')
            ),
            'value' => strtr('$data->default ? "{yes}":"{no}"', array(
                '{yes}' => Yii::t('app', 'YES'),
                '{no}' => Yii::t('app', 'NO')
            )),
        ),
        'code',
        'locale',
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
            'hidden' => array(
                'delete' => array(1),
            )
        ),
    ),
));
Yii::app()->tpl->closeWidget();
if(Yii::app()->request->isAjaxRequest){
    echo Html::closeTag('div');
}
?>