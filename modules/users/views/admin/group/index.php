<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name' => $this->pageName,
   // 'headerOptions' => false,
   // 'autoColumns'=>false,
    'selectableRows'=>false,
   // 'columns' => array(
    //    'name',
   // ),
));
if(Yii::app()->request->isAjaxRequest) echo Html::closeTag('div');

?>
