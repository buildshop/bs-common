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
            'name' => 'refer',
            'header' => 'Referer',
            'type' => 'raw',
        ),
        array(
            'name' => 'ip',
            'header' => 'IP-адрес',
            'type' => 'raw',
        ),
        array(
            'name' => 'host',
            'header' => 'Хост',
            'type' => 'raw',
        ),
        array(
            'name' => 'user_agent',
            'header' => 'User-Agent',
            'type' => 'raw',
        ),
        array(
            'name' => 'timelink',
            'header' => 'Время / Страница',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'textL')
        ),
    )
));
?>


<?php
Yii::app()->tpl->closeWidget();
?>
