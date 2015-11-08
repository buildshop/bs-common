<div class="formRow">
    <div class="grid2">
        <?php echo Yii::t('ShopModule.admin', 'Поиск:') ?>
    </div>
    <div class="grid10">
        <input type="text" onkeyup='$("#ShopCategoryTree").jstree("search", $(this).val());' />
    </div>
    <div class="clear"></div>
</div>

<?php
// Register scripts
Yii::app()->clientScript->registerScriptFile(
        $this->module->assetsUrl . '/admin/products.js', CClientScript::POS_END
);

// Create jstree
$this->widget('mod.shop.widgets.jstree.JsTree', array(
    'id' => 'ShopCategoryTree',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->active()->findAllByPk(1)),
    'options' => array(
        'core' => array(
            // Open root
            'initially_open' => 'ShopCategoryTreeNode_1',
        ),
        'plugins' => array('themes', 'html_data', 'ui', 'crrm', 'search', 'checkbox', 'cookies'),
        'checkbox' => array(
            'two_state' => true,
        ),
        'cookies' => array(
            'save_selected' => false,
        )
    ),
));

// Get categories preset
if ($model->type) {
    $presetCategories = unserialize($model->type->categories_preset);
    if (!is_array($presetCategories))
        $presetCategories = array();
}

if (isset($_POST['categories']) && !empty($_POST['categories'])) {
    foreach ($_POST['categories'] as $id) {
        Yii::app()->getClientScript()->registerScript("checkNode{$id}", "
			$('#ShopCategoryTree').checkNode({$id});
		");
    }
} elseif ($model->isNewRecord && empty($_POST['categories']) && isset($presetCategories)) {
    foreach ($presetCategories as $id) {
        if ($model->type && $id === $model->type->main_category)
            continue;

        Yii::app()->getClientScript()->registerScript("checkNode{$id}", "
			$('#ShopCategoryTree').checkNode({$id});
		");
    }
}
else {
    // Check tree nodes
    foreach ($model->categories as $c) {
        if ($c->id === $model->main_category_id)
            continue;

        Yii::app()->getClientScript()->registerScript("checkNode{$c->id}", "
			$('#ShopCategoryTree').checkNode({$c->id});
		");
    }
}

Yii::app()->getClientScript()->registerCss("ShopCategoryTreeStyles", "#ShopCategoryTree { width:90% }");
