
<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'enableCustomActions' => false,
    'selectableRows' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'columns' => array(
        array(
            'header' => '',
            'type' => 'html',
            'value' => 'Html::tag("i",array("class"=>$data->info->icon),"")',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '($data->info->url) ? Html::link(CHtml::encode($data->info->name), $data->info->url) : Html::encode($data->info->name)',
        ),
        array(
            'name' => 'access',
            'type' => 'html',
            'value' => 'Yii::app()->access->getName($data->access)',
        ),
        array(
            'name' => 'description',
            'value' => 'Html::encode($data->info->description)',
            'header' => Yii::t('app', 'DESCRIPTION'),
        ),
        array(
            'header' => Yii::t('app', 'VERSION'),
            'type' => 'raw',
            'value' => '$data->info->version',
        ),
        array(
            'header' => Yii::t('app', 'AUTHOR'),
            'type' => 'raw',
            'value' => '$data->info->author'
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
            'hidden' => array(
                'delete' => array(1, 2, 3),
                'switch' => array(1, 2, 3),
                'update' => array(1, 2, 3),
            )
        ),
    ),
));
Yii::app()->tpl->closeWidget();
if(Yii::app()->request->isAjaxRequest){
    echo Html::closeTag('div');
}
?>
