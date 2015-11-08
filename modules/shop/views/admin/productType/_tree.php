<div class="form-group">
    <div class="col-sm-4">
        <label class="control-label" for="tree-search"><?php echo Yii::t('ShopModule.admin', 'Поиск:') ?></label>
    </div>
    <div class="col-sm-8">
        <input id="tree-search" class="form-control" type="text" onkeyup='$("#ShopTypeCategoryTree").jstree("search", $(this).val());' />
    </div>
</div>
<div class="form-group clearfix">
    <div class="col-sm-12">
        <?= Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "TYPE_PRODUCT_ALERT_INFO"), false); ?>
    </div>
</div>
<?php
// Create jstree


$cats = ShopCategory::model()->findByPk(1);
$this->widget('mod.shop.widgets.jstree.JsTree', array(
    'id' => 'ShopTypeCategoryTree',
    'data' => ShopCategoryNode::fromArray($cats->children()->findAll()),
    'options' => array(
        "themes" => array("stripes" => true),
        'core' => array(
            // Open root
            'initially_open' => 'ShopTypeCategoryTreeNode_1',
        ),
        'plugins' => array('themes', 'html_data', 'ui', 'crrm', 'search', 'checkbox', 'cookies'),
        'checkbox' => array(
            'two_state' => true,
        ),
        'cookies' => array(
            'save_selected' => false,
        ),
        'ui' => array(
            'initially_select' => 'ShopTypeCategoryTreeNode_' . $model->main_category,
        ),
    ),
));

// Check tree nodes
$categories = unserialize($model->categories_preset);
if (!is_array($categories))
    $categories = array();
foreach ($categories as $id) {
    Yii::app()->getClientScript()->registerScript("checkNode{$id}", "
		$('#ShopTypeCategoryTree').checkNode({$id});
	");
}
?>
