<?php

Yii::import('mod.shop.ShopModule');

class CompareModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.components.*',
        ));
    }

    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerScriptFile($baseUrl . '/js/compare.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function getRules() {
        return array(
            'compare' => array('compare/default/index'),
            'compare/add/<id>' => array('compare/default/add'),
            'compare/remove/<id>' => array('compare/default/remove'),
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('CompareModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-compare',
            'description' => Yii::t('CompareModule.default', 'MODULE_DESC'),
        );
    }

}
