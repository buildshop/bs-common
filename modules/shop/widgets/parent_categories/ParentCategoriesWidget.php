<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class ParentCategoriesWidget extends CWidget {

    public $id = 1;

    public function run() {

        $items = ShopCategory::model()->language(Yii::app()->language->active)->findByPk($this->id)->menuArray();
        if (isset($items)) {
            $this->render('default', array('items' => $items['items']));
        } else {
            die("ParentCategoriesWidget: не могу найти root category.");
        }

        
    }

}
