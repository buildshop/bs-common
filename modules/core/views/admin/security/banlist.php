<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns'=>false,
    'columns' => array(
        array('class' => 'CCheckBoxColumn'),
        array(
            'name' => 'ip_address',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textL'),
            'value' => '$data->ip_address',
        ),
        array(
            'name' => 'reason',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textL'),
            'value' => '$data->reason',
        ),
        array(
            'name' => 'time',
            'type' => 'raw',
            'value' => '$data->getBanTime($data->time)',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));

?>
