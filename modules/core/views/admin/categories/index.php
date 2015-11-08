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
        'name',
        array(
            'name' => 'module',
            'value' => 'Yii::app()->getModule($data->module)->info["name"]',
        ),
        array(
            'name' => 'parent_id',
            'value' => '($data->parent_id)?Yii::t("core","YES"):Yii::t("core","NO")',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
if(Yii::app()->request->isAjaxRequest) echo Html::closeTag('div');

?>
