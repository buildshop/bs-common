<?php

class YandexMarketModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            Yii::app()->settings->set('yandexMarket', array(
                'name' => 'Демо магазин',
                'company' => 'Демо кампания',
                'url' => 'http://demo-company.loc/',
                'currency_id' => 3,
            ));
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('yandexMarket');
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            '/yandex-market.xml' => '/yandexMarket/default/index',
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('YandexMarketModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('/admin/yandexMarket'),
                        'active' => ($c == 'yandexMarket') ? true : false,
                        'icon' => 'icon-basket',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('YandexMarketModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'icon-basket',
            'description' => Yii::t('YandexMarketModule.default', 'MODULE_DESC'),
            'config_url' => Yii::app()->createUrl('/admin/yandexMarket'),
            'url' => Yii::app()->createUrl('/shop/'),
        );
    }

}
