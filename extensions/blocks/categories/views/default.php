<?php

$this->widget('zii.widgets.CMenu', array(
    'items' => $this->getMenuTree(),
    'htmlOptions' => array('class' => 'list'),
    'activeCssClass'=>'active',
    'itemCssClass'=>'list-group-item2',
));
?>

