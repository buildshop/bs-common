<?php

class SmsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
            $this->id . '.components.services.*',
            $this->id . '.components.services.turbosms.*',
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
                        'label' => Yii::t('core', 'SmsModule'),
                        'url' => Yii::app()->createUrl('/admin/sms'),
                        'icon' => 'flaticon-sms1',
                        'active' => ($c == 'xml') ? true : false,
                       // 'visible'=>Yii::app()->user->plan['xml']
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
            'name' => Yii::t('SmsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-sms1',
            'description' => Yii::t('SmsModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/admin/sms'),
        );
    }

}
