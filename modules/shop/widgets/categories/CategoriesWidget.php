<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class CategoriesWidget extends CWidget {

    public $htmlOptions = array();
    public $totalCount = true;
    public $itemOptions = array();
    public $submenuHtmlOptions = array();

    public function init() {
        //$this->publishAssets();
    }

    public function run() {
        Yii::import('mod.shop.models.ShopCategory');

        $model = ShopCategory::model()
                ->findByPk(1);

        if (!$model) {
            Yii::app()->tpl->alert('danger','Необходимо добавать категорию');

        } else {
            $result = $model->menuArray();
            $this->render($this->skin, array('result' => $result));
        }
        
    }


    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerCssFile($baseUrl . '/menu.css');
            $cs->registerScriptFile($baseUrl . '/menu.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
