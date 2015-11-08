<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'name' => $this->pageName,
    'enableHeader' => true,
    'selectableRows' => false,
    'autoColumns' => false,
    'columns' => array(
        'alias_key',
'switch',
        'DEFAULT_CONTROL' => array(
            'class' => 'ButtonColumn',
            'template' => '{update}',
        ),
    )
));
?>