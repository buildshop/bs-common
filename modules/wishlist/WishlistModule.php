<?php
//Yii::import('mod.shop.ShopModule');
class WishlistModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }
    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerScriptFile($baseUrl . '/js/wishlist.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }
    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            Yii::app()->database->import($this->id);
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function afterUninstall() {
        $db = Yii::app()->db;
        $tablesArray = array(
            Wishlist::model()->tableName(),
            WishlistProducts::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'wishlist' => array('wishlist/default/index'),
            'wishlist/add/<id>' => array('wishlist/default/add'),
            'wishlist/remove/<id>' => array('wishlist/default/remove'),
            'wishlist/view/<key>' => array('wishlist/default/view'),
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('WishlistModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-heart',
            'description' => Yii::t('WishlistModule.default', 'MODULE_DESC'),
        );
    }

}
