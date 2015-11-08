<?php

$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $data,
    'enableCustomActions' => false,
    'selectableRows' => false,
    'enablePagination' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'columns' => array(
        array(
            'class' => 'SGridIdColumn',
            'type' => 'html',
            'htmlOptions' => array('class' => 'image'),
            'value' => '(!empty($data->mainImage))?Html::link(Html::image($data->mainImage->getUrl("50x50"),""),$data->mainImage->getUrl("500x500")):"no image"'
        ),
        array(
            'name' => 'name',
            'value' => '$data->name',
            'htmlOptions' => array('class' => 'textL')
        ),
        array(
            'header' => 'Просмотров',
            'value' => '$data->views',
        ),
        array(
            'header' => 'Доб. в корзину',
            'value' => '$data->added_to_cart_count',
        ),
    ),
));
?>
<div class="clear"></div>
