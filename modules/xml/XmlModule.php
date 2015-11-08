<?php

class XmlModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.components.*',
        ));
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('core', 'Автоматизация XML'),
                        'url' => Yii::app()->createUrl('/admin/xml'),
                        'icon' => 'flaticon-xml',
                        'active' => ($c == 'xml') ? true : false,
                        'visible'=>Yii::app()->user->plan['xml']
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('ext.mbmenu.AdminMenu');
        $mod = new AdminMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('XmlModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-xml',
            'description' => Yii::t('XmlModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/admin/xml'),
        );
    }

}
