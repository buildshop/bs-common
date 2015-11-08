<?php

class Exchange1cModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            Yii::app()->settings->set('exchange1c', array(
                'ip' => '127.0.0.1',
                'password' => sha1(microtime())
            ));
            Yii::app()->database->import($this->id);
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('exchange1c');
        $db = Yii::app()->db;
        $db->createCommand()->dropTable('{{exchange1c}}');
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'exchange1c/<password>' => 'exchange1c/default/index',
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('Exchange1cModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('admin/exchange1c'),
                        'active' => ($c == 'exchange1c') ? true : false,
                        'icon' => 'flaticon-1c',
                        'visible' => Yii::app()->user->isSuperuser
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
            'name' => Yii::t('Exchange1cModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'fa fa-exchange',
            'description' => Yii::t('Exchange1cModule.default', 'MODULE_DESC'),
            'config_url' => Yii::app()->createUrl('/admin/exchange1c'),
            'url' => Yii::app()->createUrl('/exchange1c/'),
        );
    }

}
