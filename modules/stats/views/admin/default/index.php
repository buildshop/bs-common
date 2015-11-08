

<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));



$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'date',
            'header' => 'Дата',
            'type' => 'raw',
        ),
        array(
            'name' => 'graphic',
            'header' => 'График',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'textL')
        ),
        array(
            'name' => 'hosts',
            'header' => 'Хосты',
            'type' => 'raw',
        ),
        array(
            'name' => 'hits',
            'header' => 'Хиты',
            'type' => 'raw',
        ),
        array(
            'name' => 'search',
            'header' => 'С поиска',
            'type' => 'raw',
        ),
        array(
            'name' => 'sites',
            'header' => 'С др. сайтов',
            'type' => 'raw',
            //'htmlOptions'=>array('class'=>'textL')
        ),
    )
));
?>


<?php
Yii::app()->tpl->closeWidget();
?>

