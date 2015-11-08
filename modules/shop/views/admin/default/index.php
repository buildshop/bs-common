<?php
if (isset($this->module->adminMenu['system'])) {
    echo Html::openTag('ul', array('class' => 'middleNavA'));
    foreach ($this->module->adminMenu['system']['items'] as $key => $item) {
        echo Html::openTag('li');
        echo Html::link('<span class="iconb ' . $item['icon'] . '"></span><span>' . $item['label'] . '</span>', $item['url']);
        echo Html::closeTag('li');
    }
    echo Html::closeTag('ul');
    ?>
    <div class="divider"><span></span></div>
    <?php
}
?>





<?php
$this->widget('mod.shop.widgets.jstree2.JsTree', array(
    'id' => 'ShopCategoryTreeFilter',
    'options' => array(
        "core" => array(
            "animation" => 0,
            "check_callback" => true,
            "themes" => array("stripes" => true),
            'data' => array(
                'url' => 'default/ajaxRoot',
                'data' => 'js:function (node) {
                        return { "id" : 1 };
                    }'
            )
        ),


        'plugins' => array('themes', 'dnd', 'search', 'cookies', 'contextmenu'),
        'contextmenu' => array(

            'items' => array(
                'view' => array(
                    'label' => Yii::t('ShopModule.admin', 'Перейти'),
                    'action' => 'js:function(obj){ CategoryRedirectToFront(obj); }'
                ),
                'products' => array(
                    'label' => Yii::t('ShopModule.admin', 'Продукты'),
                    'action' => 'js:function(obj){ CategoryRedirectToAdminProducts(obj); }',
                    'icon' => 'icon-cart-3'
                ),
                //'create'=>false,
                'create' => array(
                    'label' => Yii::t('core', 'CREATE', 1),
                    'action' => 'js:function(obj){ CategoryRedirectToParent(obj); }',
                    'icon' => 'icon-plus'
                ),
                'rename' => false,
                'remove' => array(
                    'label' => Yii::t('core', 'DELETE'),
                    'icon' => 'icon-trashcan'
                //'action'=>'js:function(obj){ CategoryRename(obj); }'
                ),
                'switch' => array(
                    'label' => Yii::t('core', 'SWITCH'),
                    'icon' => 'icon-eye'
                //'action'=>'js:function(obj){ CategoryStatus(obj); }'
                ),

            )
        )
    )
));
