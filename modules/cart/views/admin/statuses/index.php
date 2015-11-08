

<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns' => false,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("/admin/cart/statuses/update", "id"=>$data->id))',
        ),
        'color',
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        /* 'hidden'=>array(
          'delete'=>array(1,2,3,4),
          'update'=>array(1,2,3,4),
          ) */
        ),
    ),
));
?>
