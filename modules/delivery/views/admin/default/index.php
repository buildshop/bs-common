<?php

Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $deliveryRecord->search(),
    'enableCustomActions' => false,
    'selectableRows' => false,
    'autoColumns'=>false,
    'columns' => array(
        array('name' => 'email', 'type' => 'raw', 'value' => 'Html::link("$data->email","javascript:void(0)", array("onClick"=>"geoip(\"$data->email\")"))'),
        array('name' => 'date_create', 'type' => 'html', 'value' => 'CMS::date("$data->date_create")'),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>
