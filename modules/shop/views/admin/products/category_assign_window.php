<?php

$this->widget('mod.shop.widgets.jstree.JsTree', array(
    'id' => 'CategoryAssignTreeDialog',
    'options' => array(
        'plugins' => array('themes', 'html_data', 'ui', 'crrm', 'checkbox', 'search'),
        'core' => array(
            // Open root
            'initially_open' => 'CategoryAssignTreeDialogNode_1',
        ),
        'checkbox' => array(
            'two_state' => true,
        ),
        'cookies' => array(
            'save_selected' => true,
        ),
    ),
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->cache($this->cacheTime)->active()->findAllByPk(1)),
));
?>

