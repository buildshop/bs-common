<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class MainCategoriesWidget extends CWidget {

    public function run() {
       // Yii::import('mod.shop.models.ShopCategory');
        $items = ShopCategory::model()->cache(Yii::app()->controller->cacheTime)->active()->findByPk(1)->menuArray();
        //$items = ShopCategory::model()->active()->findByPk(1);
        
        $this->render('render', array('model' => $items));
    }

}
