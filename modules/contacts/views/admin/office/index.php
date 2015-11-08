<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'autoColumns'=>false,
    'enableHeader'=>false,
    'filter'=>$model,
    'columns' => array(
        array(
            'name' => 'address',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->address), array("update", "id"=>$data->id))',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',

        ),
    ),
));

Yii::app()->tpl->closeWidget();
?>



