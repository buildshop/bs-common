<div class="list-group">
<?php

$this->widget('ListView', array(
    'dataProvider' => $provider,
    'id' => 'NewsLatastBlock',
    'enablePagination' => false,
    'separator' => '',
    'ajaxUpdate' => true,
    'template' => '{items}',
    'itemView' => '_list',
    'pager' => array(
        'header' => ''
    ),
));
?>
</div>